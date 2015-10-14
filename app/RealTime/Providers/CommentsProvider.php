<?php namespace Agency\RealTime\Providers;

use Api;
use App;
use Auth;
use Lang;
use TokenAuth;
use Vinelab\Minion\Dictionary;
use Agency\RealTime\Content;
use Agency\RealTime\Pagination;
use Agency\Contracts\UserInterface;
use Agency\Exceptions\UnAuthorizedException;

use Agency\User;
use Cache;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class CommentsProvider extends Provider {
    /**
     * The prefix to be used with events.
     *
     * @var string
     */
    protected $prefix = 'comments.';

    /**
     * The comments repository instance.
     *
     * @var \Fahita\Contracts\Repositories\CommentRepositoryInterface
     */
    protected $comments;

    /**
     * Set up this class.
     *
     * @return  void
     */
    protected function setUp()
    {
        $this->cache     = App::make('Agency\Contracts\Caching\CommentsCacheInterface');
        $this->comments  = App::make('Agency\Contracts\Services\CommentsServiceInterface');
    }

    /**
     * Boot up this class. This is the best place to have our registrations and pub/sub stuff.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->setUp();

        $this->register('get', 'get');
        $this->register('add', 'add');
        $this->register('like', 'like');
        $this->register('unlike', 'unlike');
        $this->register('report', 'report');
        $this->register('report.drop', 'dropReport');
        $this->register('moderation.get', 'getForModeration');
        $this->register('moderation.spam', 'spam');
        $this->register('moderation.unspam', 'unspam');
    }

    /**
     * Get the list of comments for a given content.
     *
     * @param array $args
     * @param \Vinelab\Minion\Dictionary $data
     *
     * @return void
     */
    public function get($args, Dictionary $data)
    {
        TokenAuth::loginWithToken($data->access_token);

        $content = Content::make($data);

        return Api::content(
            ['CommentMapper', 'mapFromCache'],
            $this->comments->get(
                $content,
                Pagination::make($data)
            ),
            $this->comments->totalForContent($content)
        );
    }

    /**
     * Get comments for moderation.
     *
     * @param  array     $args
     * @param  \Vinelab\Minion\Dictionary $data
     *
     * @return array
     * @throws UnAuthorizedException If the provided access token was not valid.
     */
    public function getForModeration($args, Dictionary $data)
    {
        if (TokenAuth::isModerator($data->access_token)) {

            return Api::content(
                ['CommentMapper', 'mapForModeration'],
                $this->comments->getForModeration($data->offset),
                $this->comments->total()
            );
        }

        throw new UnAuthorizedException();
    }

    /**
     * Add a comment.
     *
     * @param array     $args
     * @param \Vinelab\Minion\Dictionary $data
     *
     * @return array
     */
    public function add($args, Dictionary $data)
    {
        if (TokenAuth::loginWithToken($data->access_token)) {

            $content = Content::make($data);

            $comment = $this->comments->add($data->comment, $content, TokenAuth::user());

            $response = (array) Api::content(['CommentMapper', 'mapNewInstance'], $comment);

            $this->publish($this->getContentActionKey($content->id(), 'add'), [], $response['data']);
            $this->publishToModeration('add', $response['data']);

            return $response;
        }

        throw new UnAuthorizedException();
    }

    /**
     * Like the given comment.
     *
     * @param  array     $args
     * @param  \Vinelab\Minion\Dictionary $data
     *
     * @return array
     */
    public function like($args, Dictionary $data)
    {
        return $this->performAction('like', $data);
    }

    /**
     * Unlike a previously liked comment.
     *
     * @param  array      $args
     * @param  \Vinelab\Minion\Dictionary $data
     *
     * @return array
     */
    public function unlike($args, Dictionary $data)
    {
        return $this->performAction('unlike', $data);
    }

    /**
     * Report the given comment.
     *
     * @param  array     $args
     * @param  \Vinelab\Minion\Dictionary $data
     *
     * @return array
     */
    public function report($args, Dictionary $data)
    {
        return $this->performAction('report', $data);
    }

    /**
     * Drop a report on a previously reported comment.
     *
     * @param  array     $args
     * @param  \Vinelab\Minion\Dictionary $data
     *
     * @return array
     */
    public function dropReport($args, Dictionary $data)
    {
        return $this->performAction('dropReport', $data, true, 'report.drop');
    }

    /**
     * Mark a comment as spam.
     *
     * @param  array     $args
     * @param  Vinelab\Minion\Dictionary $data
     *
     * @return array
     */
    public function spam($args, Dictionary $data)
    {
        return $this->performModerationAction('spam', $data);
    }

    /**
     * Mark a comment as safe (not spam).
     *
     * @param  array     $args
     * @param  Vinelab\Minion\Dictionary $data
     *
     * @return array
     */
    public function unspam($args, Dictionary $data)
    {
        return $this->performModerationAction('unspam', $data, false);
    }

    /**
     * Perform the given moderation action using the given data.
     *
     * @param  string     $action
     * @param  Vinelab\Minion\Dictionary $data
     * @param  string     $topic
     * @param  bool    $publish
     *
     * @return bool
     */
    private function performModerationAction($action, Dictionary $data, $publish = true, $topic = null)
    {
        if (TokenAuth::isModerator($data->access_token)) {
            // when no custom topic is provided use the action instead.
            $topic = ($topic) ? $topic : $action;
            // perform the action on the comment.
            $comment = $this->comments->$action($data->comment_id);
            if ($comment) {
                if ($publish) {
                    $this->publish(
                        $this->getContentActionKey($comment->content->getKey(), $topic),
                        [],
                        ['comment_id' => $comment->getKey()]
                    );
                }

                $response = Api::content(
                    ['CommentMapper', 'mapAction'],
                    ['action' => $action, 'success' => true, 'comment_id' => $data->comment_id]
                );

                $this->publishToModeration($topic, $response);

                return $response;
            }
        }
    }

    /**
     * Perform the givne action on the given data.
     *
     * @param  string $action
     * @param  \Vinelab\Minion\Dictionary $data
     * @param string $topic The topic to be used when publishing the action's event.
     * @param bool $publish Indicate whether to publish the action's event or not.
     *
     * @return array
     */
    private function performAction($action, Dictionary $data, $publish = true, $topic = null)
    {
        if (TokenAuth::loginWithToken($data->access_token)) {
            // when no custom topic is provided use the action instead.
            $topic = ($topic) ? $topic : $action;
            // perform the action on the comment.
            $comment = $this->comments->$action($data->comment_id, TokenAuth::user());

            if ($comment) {
                // publish the given event (if any)
                if ($publish) {
                    $this->publish(
                        $this->getContentActionKey($comment->content->getKey(), $topic),
                        [],
                        ['comment_id' => $data->comment_id]
                    );
                }

                $this->publishToModeration($topic, ['comment_id' => $data->comment_id]);

                return Api::content(
                    ['CommentMapper', 'mapAction'],
                    ['action' => $action, 'success' => true, 'comment_id' => $data->comment_id]
                );
            }

            return Api::error(
                Lang::get('comments.action.failed', ['comment_id' => $data->comment_id, 'action' => $action, 'success'=> false]),
                1101
            );
        }

        throw new UnAuthorizedException();
    }

    /**
     * Publish the given topic with the given data to the moderation board.
     *
     * @param  string $topic
     * @param  array $data
     *
     * @return \React\Promise\Promise
     */
    protected function publishToModeration($topic, $data)
    {
        $this->publish("moderation.{$topic}", [], $data);
    }

    /**
     * Get the key for the given action according to the given content.
     *
     * @param  \Fahita\RealTime\Content $content
     * @param  string  $action
     *
     * @return string
     */
    public function getContentActionKey($content, $action)
    {
        return "content.{$content}.$action";
    }
}
