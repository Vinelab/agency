<?php namespace Agency\Cms\Authority\Contracts;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

interface ManagerInterface {

    public function allows(AuthorableInterface $authorable);

    public function Authorize(AuthorableInterface $authorable);

    public function access(AuthorableInterface $authorable, $resources);

    public function revoke(AuthorableInterface $authorable, $resources = array());

    public function permissions(AuthorableInterface $authorable, PrivilegableInterface $resource);
}