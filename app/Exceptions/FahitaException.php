<?php namespace Agency\Exceptions;

use RuntimeException;

/**
 * @author Mahmoud Zalt <mahmoud@vinelab.com>
 */
class FahitaException extends RuntimeException {

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
