<?php namespace Agency\Cms\Exceptions;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use RuntimeException;

class CmsException extends RuntimeException {

    /**
     * The error messages to pass.
     *
     * @var array
     */
    protected $messages;

    public function __construct($messages = array())
    {
        // add support for sending in
        // one single message as an array
        if ( ! is_array($messages))
        {
            $messages = [$messages];
        }

        $this->messages = $messages;
    }

    public function messages()
    {
        return $this->messages;
    }
}

class InvalidSectionException extends CmsException {}

class UnauthorizedException extends CmsException {}

class InvalidAdminException extends CmsException {}

class InvalidPostException extends CmsException {}

class InvalidImageException extends CmsException {}

class InvalidContentException extends CmsException {}

class InvalidVideoException extends CmsException {}

class InvalidRoleException extends CmsException {}

class InvalidPermissionException extends CmsException {}