<?php namespace Agency\Contracts\Office;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Agency\Contracts\Office\AuthorableInterface;

interface AdminAuthorizerInterface {

    public function initial(AuthorableInterface $admin, $Agency = [], $artists = []);
    public function authorize(AuthorableInterface $admin, $Agency = [], $artists = []);
	public function artistInitial( AuthorableInterface $admin, $artists = [ ] );
	public function artistAuthorize( AuthorableInterface $admin, $artists = [ ] );

}
