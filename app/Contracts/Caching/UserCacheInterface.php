<?php namespace Agency\Contracts\Caching;

interface UserCacheInterface {

    /**
     * Get the cached user data for the given id.
     *
     * @param  string $id
     *
     * @return mixed
     */
    public function get($id);
}
