<?php namespace Agency\Office\Notifications;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Illuminate\Mail\Mailer;
use Agency\Contracts\Office\RegistrableInterface;
use Agency\Contracts\Office\Notifications\AdminRegistrationNotifierInterface;

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
