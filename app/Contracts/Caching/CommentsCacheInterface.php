<?php namespace Agency\Contracts\Caching;

use Agency\RealTime\Content;
use Agency\Comment;
use Agency\RealTime\Pagination;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface CommentsCacheInterface {

    /**
     * Push a comment to the given content cache list.
     *
     * @param \Fahita\Comment $comment
     * @param \Fahita\RealTime\Content $content
     *
     * @return boolean
     */
   public function add(Comment $comment, Content $content);

   /**
     * Get the comments for the given content within the given pagination's boundaries.
     *
     * @param  \Fahita\RealTime\Content    $content
     * @param  \Fahita\RealTime\Pagination $pagination
     *
     * @return array
     */
    public function get(Content $content, Pagination $pagination);
}
