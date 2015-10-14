<?php namespace Agency\Contracts\Services;

use Agency\RealTime\Content;
use Agency\RealTime\Pagination;
use Agency\Contracts\UserInterface;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface CommentsServiceInterface {

    /**
     * Get the comments for the given content.
     *
     * @param  \Fahita\RealTime\Content     $content
     * @param  \Fahita\RealTime\Pagination $pagination
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(Content $content, Pagination $pagination);

    /**
     * Get the comments list for the given content.
     *
     * @param string $text
     * @param \Fahita\RealTime\Content $content
     * @param \Fahita\UserInterface $user
     *
     * @return \Illuminate\Database\Collection
     */
    public function add($text, Content $content, UserInterface $user);

    /**
     * Like a comment.
     *
     * @param  int $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function like($comment_id, UserInterface $user);

    /**
     * Unlike a comment.
     *
     * @param  int $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function unlike($comment_id, UserInterface $user);

    /**
     * Report a comment.
     *
     * @param  int $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function report($comment_id, UserInterface $user);

    /**
     * Drop report on a comment.
     *
     * @param  int $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function dropReport($comment_id, UserInterface $user);
}
