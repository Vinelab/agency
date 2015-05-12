<?php namespace Agency\Auth;

use Lang;
use Agency\Auth\Verifier;
use Illuminate\Mail\Mailer as IlluminateMailer;

/**
 * @category Utility
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Mailer
{
    /**
     * The mailer instance.
     *
     * @var \Illuminate\Mail\Mailer
     */
    protected $mailer;

    public function __construct(IlluminateMailer $mailer, Verifier $verifier)
    {
        $this->mailer = $mailer;
        $this->verifier = $verifier;
    }

    /**
     * Send the email for user's email address verification.
     *
     * @param string $name
     * @param string $email
     *
     * @return string
     */
    public function sendVerificationEmail($name, $email)
    {
        // generate verification code
        $verification_code = $this->verifier->newCode($email);
        // send verification email
        $this->mailer->send(
            'emails.auth.verification',
            compact('name', 'email', 'verification_code'),
            function ($message) use($name, $email) {
                $message->to($email, $name)->subject(Lang::get('email.email_verification'));
            }
        );

        return $verification_code;
    }
}
