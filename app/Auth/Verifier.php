<?php namespace Agency\Auth;

use Illuminate\Encryption\Encrypter;
use Illuminate\Cache\Repository as Cache;
use Agency\Contracts\Repositories\UserRepositoryInterface as Users;
use Agency\Contracts\Repositories\VerificationCodeRepositoryInterface as VerificationCodes;

/**
 * @category Utility
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Verifier
{
    const STATUS_FAILED = 'failed';
    const STATUS_SUCCESS = 'success';
    const STATUS_RENEWED = 'renewed';
    /**
     * Constructor.
     *
     * @param Illuminate\Ecnryption\Encrypter $encrypter
     * @param \Illuminate\Cache\Repository    $cache
     */
    public function __construct(Encrypter $encrypter, Cache $cache, Users $users, VerificationCodes $codes)
    {
        $this->cache = $cache;
        $this->codes = $codes;
        $this->users = $users;
        $this->crypt = $encrypter;
    }

    /**
     * Get a new verification code.
     *
     * @param  string $value The value you want stored with the code
     * @param  int $ttl Must be numeric and greater than 0
     *
     * @return string
     */
    public function newCode($value = null, $ttl = null)
    {
        // if no value is provided we use a positive bool
        $value = $value ?: true;
        // get the code
        $code = $this->generateNewCode($value);
        // get the code's TTL
        $ttl = $this->getTtl($ttl);
        // cache the code
       $this->cache->put($code, $value, $ttl);

       return $code;
    }

    /**
     * Generate a new verification code.
     *
     * @param  int|string $value
     *
     * @return string
     */
    public function generateNewCode($value)
    {
        return $this->crypt->encrypt($value);
    }

    /**
     * Get the value stored for the given code.
     *
     * @param  string $code
     *
     * @return mixed
     */
    public function getValueForCode($code)
    {
        $exists = $this->exists($code);

        if ($exists) {
            return $this->crypt->decrypt($code);
        }
    }

    /**
     * Get the user for which this code belongs.
     *
     * @param string $code
     *
     * @return \Agency\User
     */
    public function getUserForCode($code)
    {
        $code = $this->codes->findByCode($code);

        return $code->user;
    }

    public function verifyEmail($email, $code)
    {
        $this->users->verifyEmail($email);

        $this->cache->forget($code);
    }

    /**
     * Check whether the given verification code exists.
     *
     * @param string $code
     *
     * @return bool
     */
    public function exists($code)
    {
        return $this->cache->has($code);
    }

    /**
     * Get the TTL value, override by passing the ttl value you want.
     *
     * @param  int $ttl Must be a positive value.
     *
     * @return int
     */
    public function getTtl($ttl = null)
    {
        $default = config('cache.durations.verification');

        if (!is_numeric($ttl) || (is_numeric($ttl) && $ttl < 0)) {
            $ttl = $default;
        }

        return (int) $ttl ?: $default;
    }
}
