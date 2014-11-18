<?php namespace Agency\Office\Exceptions;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use RuntimeException;

class Exception extends RuntimeException {

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
