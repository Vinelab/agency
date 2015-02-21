<?php namespace Xfactor\Exceptions;


use RuntimeException;


class XfactorException extends RuntimeException {

	 /**
     * The error messages to pass.
     *
     * @var array
     */
    protected $messages;

    public function __construct($messages = array())
    {
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