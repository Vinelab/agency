<?php namespace Agency\Contracts\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
interface VerificationCodeRepositoryInterface
{
    /**
     * Create a new code record with the given relations.
     *
     * @param string $code
     * @param array $relations
     *
     * @return \Agency\VerificationCode
     */
    public function createWith($code, array $relations);

    /**
     * Create a new verification code for the user with the given email.
     *
     * @param string $email
     * @param string $code
     *
     * @return Agency\EmailVerificationCode
     */
    public function createForEmail($email, $code);
}
