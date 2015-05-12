<?php namespace Agency\Login\Store;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface StoreInterface {

    public function put($stack, $key, $value);

    public function get($stack, $needle);

    public function remove($stack, $needle);
}
