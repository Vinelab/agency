<?php namespace Agency\Api\Auth;

use Lang;
use Illuminate\Http\Request;
use Agency\Auth\Mailer;
use Vinelab\Auth\Social\Profile;
use Agency\Api\Auth\Session;
use Agency\Contracts\Repositories\UserRepositoryInterface as Users;
use Agency\Contracts\Validators\AuthValidatorInterface as AuthValidator;
use Agency\Contracts\Repositories\SocialAccountRepositoryInterface as Accounts;
use Agency\Contracts\Repositories\VerificationCodeRepositoryInterface as VerificationCodes;

/**
 * Implements the social authentication process by:
 *     + generating user access tokens
 *     + saving logged-in user's profile
 *     + updating existing user profiles
 *     + setting the required cache keys and parameters
 *       to indicate an active session
 *
 * @category Microservice
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class SocialAuth
{
    /**
     * The user repository instance.
     *
     * @var \Agency\Contracts\Repositories\UserRepositoryInterface
     */
    protected $users;
    /**
     * The auth validator instance.
     *
     * @var \Agency\Contracts\Validators\AuthValidatorInterface
     */
    protected $validator;

    /**
     * The session instance.
     *
     * @var \Agency\Api\Auth\Session
     */
    protected $session;

    /**
     * The mailer instance.
     *
     * @var \Illuminate\Mail\Mailer
     */
    protected $mailer;

    /**
     * The verification code repository instance.
     *
     * @var \Agency\Contracts\Repositories\VerificationCodeRepositoryInterface
     */
    protected $codes;

    /**
     * Constructor.
     *
     * @param \Agency\Contracts\Repositories\UserRepositoryInterface         $users
     * @param \Agency\Contracts\Repositories\AccountRepositoryInterface      $accounts
     * @param Agency\Contracts\Validators\AuthValidatorInterface             $validator
     */
    public function __construct(
        Users $users,
        Accounts $accounts,
        Session $session,
        AuthValidator $validator,
        Mailer $mailer,
        VerificationCodes $codes
    ) {
        $this->codes     = $codes;
        $this->users     = $users;
        $this->mailer    = $mailer;
        $this->session   = $session;
        $this->accounts  = $accounts;
        $this->validator = $validator;
    }

    /**
     * Login the given user by their profile received
     * from the request's input.
     *
     * @param  string  $provider
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function login($provider, Request $request)
    {
        if ($this->validate($request)) {
            $profile = $this->getProfile($provider, $request->input());

            $name = trim($profile->name);
            $email = trim($profile->email);
            $avatar = trim($profile->avatar);
            $info = $profile->info();

            // check for user's existence by social account ID
            if ($account = $this->accounts->findBySocialId($request->input('id'))) {
                // existing user
                $user = $account->user;

                $method = $this->getUpdateMethodForProvider($provider);
                $user = $this->users->$method(
                    $user->getKey(),
                    $name,
                    $email,
                    $avatar,
                    $info
                );
            } else {
                // new user
                $method = $this->getCreateMethodForProvider($provider);
                $user = $this->users->$method(
                    $name,
                    $email,
                    $avatar,
                    $info
                );
                // for a newly registered user we will send an email address
                // verification email if we have it
                if (!empty($email)) {
                    $code = $this->mailer->sendVerificationEmail($name, $email);
                    // store verification code for user so that we can track their verification process
                    $this->codes->createForEmail($email, $code);
                }
            }
        }

        $token = $this->session->generateToken($user);
        // set the token for the user since the token is per session
        $user->access_token = $token;
        // start the session for the generated token
        $this->session->start($user);

        return $user;
    }

    /**
     * Get the method name for creating a user with a provider.
     *
     * @param  string $provider
     *
     * @return string
     */
    protected function getCreateMethodForProvider($provider)
    {
        return 'createWith'.ucfirst(strtolower($provider));
    }

    /**
     * Get the method name for updateing a user with a provider.
     *
     * @param  string $provider
     *
     * @return string
     */
    protected function getUpdateMethodForProvider($provider)
    {
        return 'update'.ucfirst(strtolower($provider));
    }

    /**
     * Validate the given request for authentication.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return bool
     *
     * @throws \Agency\Exceptions\InvalidAuthProfileException If the request does not containe a valid profile.
     */
    public function validate(Request $request)
    {
        return $this->validator->validate($request->input());
    }

    /**
     * Get a profile instance out of the given provider and attributes.
     *
     * @param  string $provider
     * @param  array|object $attributes
     *
     * @return \Vinelab\Auth\Social\Profile
     */
    public function getProfile($provider, $attributes)
    {
        $profile = new Profile();
        $profile->instantiate((object) $attributes, $provider);
        $profile->provider = $profile->provider();
        // switch 'id' attribute name with 'social_id'
        $profile->social_id = $profile->id;
        unset($profile->id);

        return $profile;
    }
}
