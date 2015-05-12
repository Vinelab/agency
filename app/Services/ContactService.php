<?php namespace Agency\Services;

use Agency\Exceptions\IncorrectFormNameException;
use Agency\Repositories\ContactRepository;
use Illuminate\Mail\Mailer as IlluminateMailer;

/**
 * Class ContactService
 *
 * @category
 * @package Agency\Services
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ContactService extends AgencyService
{

    /**
     * the gawab form name
     *
     * @var string
     */
    const TYPE_GAWAB = 'gawab';

    /**
     * the baytina form name
     *
     * @var string
     */
    const TYPE_BAYTINA = 'baytina';

    /**
     * the template name of gawab contact email
     *
     * @var string
     */
    const TEMPLATE_GAWAB = 'gawab-contact';

    /**
     * the template name of baytina contact email
     *
     * @var string
     */
    const TEMPLATE_BAYTINA = 'baytina-contact';

    /**
     * @var \Illuminate\Mail\Mailer
     */
    private $mailer;

    /**
     * the email where the form data will be sent
     *
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $subject;

    /**
     * @param \Illuminate\Mail\Mailer                    $mailer
     * @param \Agency\Repositories\ContactRepository $contacts
     */
    public function __construct(IlluminateMailer $mailer, ContactRepository $contacts)
    {
        $this->mailer = $mailer;
        $this->contacts = $contacts;

        $this->email = config('contact.email');
        $this->name = config('contact.name');
        $this->subject = config('contact.subject');
    }

    /**
     * @param string $form form name/type
     * @param        $request
     *
     * @return string|void
     */
    public function contact($form, $request)
    {
        switch ($form) {
            case self::TYPE_GAWAB:
                $result = $this->gawab($request);
                break;
            case self::TYPE_BAYTINA:
                $result = $this->baytina($request);
                break;
            default:
                throw new IncorrectFormNameException;
        };

        return $result;
    }

    /**
     * perform the Gawab form business logic
     *
     * @param $request
     *
     * @return string
     */
    private function gawab($request)
    {
        $this->sendGawabMail($request);
        $this->storeGawab($request);

        return true;
    }

    /**
     * perform the Baytina form business logic
     *
     * @param $request
     *
     * @return string
     */
    private function baytina($request)
    {
        $this->sendBaytinaMail($request);
        $this->storeBaytina($request);

        return true;
    }

    /**
     * @param $request
     *
     * @return mixed
     */
    private function storeGawab($request)
    {
        $name = $request->get('name');
        $phone = $request->get('phone');
        $country = $request->get('country');
        $message = $request->get('message');

        return $this->contacts->create(self::TYPE_GAWAB, $phone, $country, $name, $message);
    }

    /**
     * @param $request
     *
     * @return bool
     */
    private function sendGawabMail($request)
    {
        $name = $request->get('name');
        $phone = $request->get('phone');
        $country = $request->get('country');
        $message_body = $request->get('message');
        $subject = $this->getSubject($request);

        $this->mailer->send('emails.contact.' . self::TEMPLATE_GAWAB,
            compact('name', 'phone', 'country', 'message_body'),
            function ($msg) use ($subject){
                $msg->to($this->email, $this->name)->subject($subject);
            });

        return true;
    }

    /**
     * @param $request
     *
     * @return bool
     */
    private function sendBaytinaMail($request)
    {
        $name = $request->get('name');
        $phone = $request->get('phone');
        $country = $request->get('country');
        $message_body = $request->get('message');
        $email = $request->get('email');
        $subject = $this->getSubject($request);

        $this->mailer->send('emails.contact.' . self::TEMPLATE_BAYTINA,
            compact('name', 'phone', 'country', 'message_body', 'email'),
            function ($msg) use ($subject){
                $msg->to($this->email, $this->name)->subject($subject);
            });

        return true;
    }

    /**
     * check if the subject exist in the request otherwise return the default subject
     * from the config file
     *
     * @param $request
     *
     * @return mixed|string
     */
    private function getSubject($request)
    {
        $subject = $request->get('subject');

        return (! is_null($subject)) ? $subject : $this->subject;
    }

    /**
     * @param $request
     *
     * @return mixed
     */
    private function storeBaytina($request)
    {
        $name = $request->get('name');
        $phone = $request->get('phone');
        $country = $request->get('country');
        $message = $request->get('message');
        $email = $request->get('email');

        return $this->contacts->create(self::TYPE_GAWAB, $phone, $country, $name, $message, $email);
    }

}
