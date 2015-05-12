<?php namespace Agency\Contracts\Validators;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface CommentsValidatorInterface {

    /**
     * Validate a comment to be added.
     *
     * @param  array $attribtues
     *
     * @return boolean
     * @throws \Fahita\Exceptions\InvalidCommentException If the comment was invalid according to the rules.
     */
    public function validateAdding($attribtues);
}
