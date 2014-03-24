<?php namespace Agency\Cms\Authentication\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Cms\Authority\Contracts\AuthorableInterface;

interface AdminAuthorizerInterface {

    public function initial(AuthorableInterface $admin, $najem = [], $artists = []);
    public function authorize(AuthorableInterface $admin, $najem = [], $artists = []);

}