<?php namespace Agency\Caching;

use Config;
use TokenAuth;
use Agency\Comment;
use Agency\Content as PublishedContent;
use Agency\RealTime\Content;
use Agency\Caching\Types\Set;
use Agency\Caching\Types\Hash;
use Agency\RealTime\Pagination;
use Agency\Mappers\CommentMapper;
use Agency\Contracts\UserInterface;
use Agency\Caching\Types\SortedSet;
use Predis\Response\Status as PredisStatus;
use Agency\Contracts\Caching\CommentsCacheInterface;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class CommentsCache implements CommentsCacheInterface {

    protected $prefix = 'comments';

    /**
     * The caching sorted set instance.
     *
     * @var \Fahita\Caching\Types\SortedSet
     */
    protected $sorted;

    /**
     * The caching set instance.
     *
     * @var \Fahita\Caching\Types\Set
     */
    protected $set;

    /**
     * The caching hash instance.
     *
     * @var \Fahita\Caching\Types\Hash
     */
    protected $hash;

    /**
     * The comment mapper instance.
     *
     * @var \Fahita\Mappers\CommentMapper
     */
    protected $mapper;

    public function __construct(
        Set $set,
        Hash $hash,
        UserCache $users,
        SortedSet $sorted,
        CommentMapper $mapper
    ) {
        $this->set    = $set;
        $this->hash   = $hash;
        $this->users  = $users;
        $this->sorted = $sorted;
        $this->mapper = $mapper;

        $this->setPrefixInStores();
    }

    /**
     * Set the prefix for the caching instances.
     */
    protected function setPrefixInStores()
    {
        $this->set->setPrefix($this->prefix);
        $this->hash->setPrefix($this->prefix);
        $this->sorted->setPrefix($this->prefix);
    }

    /**
     * Push a comment to the given content cache list.
     *
     * @param \Fahita\Comment $comment
     * @param \Fahita\RealTime\Content $content
     *
     * @return boolean
     */
    public function add(Comment $comment, Content $content)
    {
        // Set the pipeline to be used by the stores.
        $pipe = $this->usePipeline();

        // add the comment id to the sorted set of comment ids with the score being the incremental id of the comment
        // so that we make sure the order of occurrence is respected.
        $this->sorted->add($content, $this->getScore($comment), $comment->getKey());

        // store the comment details in a hash under comments:{$comment->id}
        $this->hash->put($comment->getKey(), $this->mapper->mapToCache($comment));

        // execute the caching commands.
        $result = $pipe->execute();

        // close the caching pipe
        $this->closePipeline();

        // we will have an array with (int) 1 inside it, otherwise a (int) 0 will be somewhere in that array
        // indicating a failure somewhere.
        if (! in_array(0, $this->normalizeResult($result))) {
            // get the delivery mapping for the saved comment
            return $comment;
        }
    }

    /**
     * Get the comments for the given content within the given pagination's boundaries.
     *
     * @param  \Fahita\RealTime\Content    $content
     * @param  \Fahita\RealTime\Pagination $pagination
     *
     * @return array
     */
    public function get(Content $content, Pagination $pagination, $user = null)
    {
        $prefix = $this->getScriptPrefix();
        $key    = $this->sorted->getKey($this->sorted->getContentKey($content));
        $range  = $this->sorted->getPaginationRange($pagination);
        $user_id = (isset($user)) ? $user->getKey() : null;

        $result = $this->runScript('getcom', 2, $prefix, $key, $range->start(), $range->stop(), $user_id);

        return array_map(function ($comment) {
            return json_decode($comment, true);
        }, $result);
    }

    /**
     * Get the number of comments for the given content.
     *
     * @param  \Agency\RealTime\Content $content
     *
     * @return int
     */
    public function count(Content $content)
    {
        return $this->sorted->count($this->sorted->getKey($this->sorted->getContentKey($content)));
    }

    /**
     * Like a comment. This is done by adding the user id to the set of likes.
     *
     * @param  int        $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function like($comment_id, UserInterface $user)
    {
        return $this->set->add($this->getLikesKey($comment_id), $user->getKey());
    }

    /**
     * Unlike a comment. This is done by adding the user id to the set of likes.
     *
     * @param  int        $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function unlike($comment_id, UserInterface $user)
    {
        return $this->set->remove($this->getLikesKey($comment_id), $user->getKey());
    }

    /**
     * Report a comment. This is done by creating a REPORTED relationship b/w the user and the comment.
     *
     * @param  int        $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function report($comment_id, UserInterface $user)
    {
        return $this->set->add($this->getReportsKey($comment_id), $user->getKey());
    }

    /**
     * Drop a reported comment. This is done by dropping a relationship b/w the user and the comment.
     *
     * @param  int        $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function dropReport($comment_id, UserInterface $user)
    {
        return $this->set->remove($this->getReportsKey($comment_id), $user->getKey());
    }

    /**
     * Make the comment of the given id as spam.
     *
     * @param  int $comment
     *
     * @return bool
     */
    public function spam(Comment $comment)
    {
        // marking as spam is about moving the comment from the 'comments:{content}' set
        // to the `comments:{content}:spam` set.
        $content = Content::makeFromContent($comment->content);

        return $this->sorted->move($content, $this->getSpamKeyForContent($content), $comment->getKey());
    }

    /**
     * Make the comment of the given id as safe (not spam).
     *
     * @param  int $comment
     *
     * @return bool
     */
    public function unspam(Comment $comment)
    {
        // marking as spam is about moving the comment from the 'comments:{content}' set
        // to the `comments:{content}:spam` set.
        $content = Content::makeFromContent($comment->content);

        return $this->sorted->move($this->getSpamKeyForContent($content), $content, $comment->getKey());
    }

    /**
     * Get the spammed comments key for the given content.
     *
     * @param  \Agency\Content $content
     *
     * @return string
     */
    public function getSpamKeyForContent(Content $content)
    {
        return $this->sorted->getContentKey($content).':spam';
    }

    /**
     * Generate the key for likes.
     *
     * @param  int $comment_id
     *
     * @return string
     */
    protected function getLikesKey($comment_id)
    {
        return $comment_id.':likes';
    }

    /**
     * Generate the key for reports.
     *
     * @param int $comment_id
     *
     * @return string
     */
    protected function getReportsKey($comment_id)
    {
        return $comment_id.':reports';
    }

    /**
     * Get the score for the given comment.
     *
     * @param  \Fahita\Comment $comment
     *
     * @return int
     */
    public function getScore(Comment $comment)
    {
        return $comment->id;
    }

    /**
     * Make the stores use the given pipline so that their commands run when the pipe is executed.
     *
     * @return \Predis\Pipeline\PipelineContext
     */
    protected function usePipeline()
    {
        // Get the pipeline instance.
        $pipe = $this->sorted->pipeline();

        // make the cache stores use it.
        $this->set->usePipeline($pipe);
        $this->hash->usePipeline($pipe);
        $this->sorted->usePipeline($pipe);

        return $pipe;
    }

    /**
     * Make the stores stop using the pipeline and run commands directly on the server.
     *
     * @return void
     */
    protected function closePipeline()
    {
        $this->set->resetStore();
        $this->hash->resetStore();
        $this->sorted->resetStore();
    }

    protected function getScriptPrefix()
    {
        return Config::get('cache.prefix');
    }

    /**
     * Run the lua script of the given file name in the redis server context.
     *
     * @param string $filename
     * @param int $keys_number
     *
     * @return mixed
     */
    protected function runScript($filename, $keys_number = 0)
    {
        $filename = preg_replace('/\.lua$/', '', $filename).'.lua';
        $file = __DIR__."/Scripts/$filename";
        $store = $this->sorted->getStore();

        $lua = file_get_contents($file);

        $params = func_get_args();
        $params[0] = $lua;
        $params[1] = $keys_number;

        return call_user_func_array([$store, 'eval'], $params);
    }

    protected function normalizeResult(array $result)
    {
        return array_map(function ($status) {
            if ($status instanceof PredisStatus) {

                return ($status->getPayload() === "OK") ? 1 : 0;
            }

            return (int) $status;
        }, $result);
    }
}
