<?php namespace Agency\Contracts\Repositories;

use Agency\RealTime\Content;
use Agency\Contracts\UserInterface;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface CommentRepositoryInterface {

    /**
     * Create a new comment in the database.
     *
     * @param string $text The comment text
     * @param string $type The content type
     * @param string $id The content id
     * @param \Fahita\Contrats\UserInterface $user The creator's user instance
     *
     * @return \Fahita\Comment
     */
    public function create($text, $type, $id, UserInterface $user);

    /**
     * Like a comment. This is done by relating the user to the comment using the "likes" relation.
     *
     * @param  int $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function like($comment_id, UserInterface $user);

    /**
     * Unlike a comment. This is done by detaching the "likes" relationship b/w the user and the comment.
     *
     * @param  int        $comment_id
     * @param  \Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function unlike($comment_id, UserInterface $user);

    /**
     * Report a comment. This is done by creating a "reported" relationship b/w the user and the comment.
     *
     * @param  int        $comment_id
     * @param  Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function report($comment_id, UserInterface $user);

    /**
     * Drop a report from a comment. This is done by detaching the "reported" relationship b/w the user and the comment.
     *
     * @param  int        $comment_id
     * @param  Fahita\Contracts\UserInterface $user
     *
     * @return bool
     */
    public function dropReport($comment_id, UserInterface $user);
}
