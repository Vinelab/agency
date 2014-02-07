<?php namespace Agency\Cms\Authority\Exceptions;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use RuntimeException;

class AuthorityException extends RuntimeException {}

class RoleNotFoundException extends AuthorityException {}

class PermissionNotFoundException extends AuthorityException {}

class InvalidResourceTypeException extends AuthorityException {}