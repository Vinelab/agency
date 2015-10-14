<?php namespace Agency\Cms\Notifications;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Mail\Mailer;
use Agency\Contracts\Cms\RegistrableInterface;
use Agency\Contracts\Cms\Notifications\AdminRegistrationNotifierInterface;

class AdminRegistrationEmailNotifier implements AdminRegistrationNotifierInterface {

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
}
