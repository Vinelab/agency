<?php namespace Agency\Services;

use Config;
use TokenAuth;
use Agency\Comment;
use Agency\RealTime\Content;
use Agency\RealTime\Pagination;
use Agency\Contracts\UserInterface;
use Agency\Contracts\Services\CommentsServiceInterface;
use Agency\Contracts\Caching\CommentsCacheInterface as Cache;
use Agency\Contracts\Repositories\CommentRepositoryInterface as Comments;
use Agency\Contracts\Validators\CommentsValidatorInterface as CommentsValidator;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class CommentsService implements CommentsServiceInterface {

    /**
     * The cache instance.
     *
     * @var \Fahita\Contracts\Caching\CommentsCacheInterface
     */
    protected $cache;

    /**
     * The comments repository instance.
     *
     * @var \Fahita\Contracts\Repositories\CommentRepositoryInterface
     */
    protected $comments;

    /**
     * The comments validator instance.
     *
     * @var \Fahita\Contracts\Validators\CommentsValidatorInterface
     */
    protected $validator;

    public function __construct(Comments $comments, Cache $cache, CommentsValidator $validator)
    {
        $this->cache = $cache;
        $this->comments = $comments;
        $this->validator = $validator;
    }

    /**
     * Get the comments for the given content.
     *
     * @param  \Fahita\RealTime\Content     $content
     * @param  \Fahita\RealTime\Pagination $pagination
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(Content $content, Pagination $pagination)
    {
        return $this->cache->get($content, $pagination, TokenAuth::user());
    }

    /**
     * Get the comments for moderation skipping the given offset.
     *
     * @param  int $offset
     *
     * @return \Illuminate\Support\Collection
     */
    public function getForModeration($offset)
    {
        $offset = ($offset && is_numeric($offset)) ? $offset : 0;
        $limit = Config::get('comments.moderation.limit');

        return $this->comments->get($limit, $offset, ['content', 'likes', 'reports', 'user']);
    }

    /**
     * Get the total number of comments.
     *
     * @return int
     */
    public function total()
    {
        return $this->comments->count();
    }

    /**
     * Get the total number of comments for the given content.
     *
     * @param  \Agency\RealTime\Content $content
     *
     * @return int
     */
    public function totalForContent(Content $content)
    {
        return $this->cache->count($content);
    }

    /**
     * Add the given comment to the content.
     *
     * @param string                          $text
     * @param \Fahita\RealTime\Content                 $content
     * @param \Fahita\Contracts\UserInterface $user
     *
     * @return array|null
     */
    public function add($text, Content $content, UserInterface $user)
    {
        if ($this->validator->validateAdding(compact('text'))) {

            // store the comment in the database
            $comment = $this->comments->create($text, $content->type(), $content->id(), $user);

            // cache the created comment
            return $this->cache->add($comment, $content);
        }
    }

    /**
     * Like a comment.
     *
     * @param  int $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function like($comment_id, UserInterface $user)
    {
        return $this->performAction('like', $comment_id, $user);
    }

    /**
     * Unlike a comment.
     *
     * @param  int $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function unlike($comment_id, UserInterface $user)
    {
        return $this->performAction('unlike', $comment_id, $user);
    }

    /**
     * Report a comment.
     *
     * @param  int $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function report($comment_id, UserInterface $user)
    {
        return $this->performAction('report', $comment_id, $user);
    }

    /**
     * Drop report on a comment.
     *
     * @param  int $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function dropReport($comment_id, UserInterface $user)
    {
        return $this->performAction('dropReport', $comment_id, $user);
    }

    /**
     * Mark a comment as spam.
     *
     * @param  StdClass $comment
     *
     * @return \Fahita\Comment | null
     */
    public function spam($comment)
    {
        return $this->performModerationAction('spam', $comment);
    }

    /**
     * Mark a comment as safe (not spam).
     *
     * @param  StdClass $comment
     *
     * @return \Fahita\Comment | null
     */
    public function unspam($comment)
    {
        return $this->performModerationAction('unspam', $comment);
    }

    /**
     * Perform the given action.
     *
     * @param  string     $action
     * @param  int        $id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function performAction($action, $id, UserInterface $user)
    {
        // perform the action in the database.
        $comment = $this->comments->$action($id, $user);

        // perform the action in the cache.
        if ($this->cache->$action($id, $user)) {

            return $comment;
        }
    }

    /**
     * Perform the given moderation action on the given comment ID.
     *
     * @param  string $action
     * @param  string|int $comment_id
     *
     * @return \Agency\Comment
     */
    public function performModerationAction($action, $comment_id)
    {
        $comment = $this->comments->$action($comment_id);

        if ($this->cache->$action($comment)) {

            return $comment;
        }
    }

    /**
     * Get the method name to be called based on the content type.
     *
     * @param \Fahita\RealTime\Content $content
     *
     * @return string
     */
    protected function getMethodForContent(Content $content)
    {
        switch ($content->type())
        {
            case Content::TYPE_VIDEO:
                $method = 'video';
            break;

            case Content::TYPE_ARTICLE:
                $method = 'article';
            break;

            case Content::TYPE_PHOTO_ALBUM:
                $method = 'photoAlbum';
            break;
        }

        return $method;
    }

    /**
     * Get the cache key based on the given content instance.
     *
     * @param  \Fahita\RealTime\Content $content
     *
     * @return string
     */
    protected function getCacheKeyForContent(Content $content)
    {
        return ':'.$this->cache_prefix.':'.$content->type().':'.$content->id();
    }
}
