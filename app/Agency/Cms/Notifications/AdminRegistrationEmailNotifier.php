<?php namespace Agency\Cms\Notifications;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Mail\Mailer;
use Agency\Cms\Contracts\RegistrableInterface;

class AdminRegistrationEmailNotifier implements Contracts\AdminRegistrationNotifierInterface {

    /**
     * The mailer instance.
     *
     * @var Illuminate\Mail\Mailer
     */
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notify(RegistrableInterface $admin)
    {
        $password = $admin->getRegistrationPassword();

        $this->mailer->send('emails.cms.registration', compact('password'), function($message) use($admin) {
            $message->to($admin->getRegistrationEmail(), $admin->getName())->subject('Welcome!');
        });
    }

    public function sendCode(RegistrableInterface $admin)
    {
        $this->mailer->send(
            'emails.cms.resetPassword',
            compact('admin'),
            function($message) use($admin) {
            $message->to($admin->getRegistrationEmail(), $admin->getName())->subject('Reset Password');
            }
        );
    }
}