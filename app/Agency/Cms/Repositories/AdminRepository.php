<?php namespace Agency\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Hash;
use Agency\Cms\Admin;
use Agency\Helper;
use Agency\Contracts\Cms\Repositories\AdminRepositoryInterface;

class AdminRepository extends Repository implements AdminRepositoryInterface {

    /**
     * The admin model instance.
     *
     * @var Agency\Cms\Admin
     */
    protected $admin;

    public function __construct(Admin $admin)
    {
        $this->model = $this->admin = $admin;
    }

    /**
     * Create a new Admin.
     *
     * @param  string $name
     * @param  string $email
     * @return Agency\Cms\Admin
     */
    public function create($name, $email)
    {
        $raw = $this->generatePassword();

        $password  = Hash::make($raw);

        $admin = $this->admin->create(compact('name', 'email', 'password'));

        return $admin;
    }

    /**
     * Update an existing Admin.
     *
     * @param  mixed $id
     * @param  string $name
     * @param  string $email
     * @return Agency\Cms\Admin
     */
    public function update($id, $name, $email)
    {
        $admin = $this->find($id);
        $admin->fill(compact('name', 'email'));

        $admin->save();

        return $admin;
    }

    /**
     * Generates a random password.
     *
     * @return string
     */
    public function generatePassword()
    {

        return uniqid();
    }

    public function changePassword($id,$new_password)
    {
        $admin = $this->find($id);
        $admin->password = Hash::make($new_password);

        if ($admin->save())
        {
            return $admin;
        }
    }

    public function resetPassword($id)
    {
        $password = $this->generatePassword();
        $admin = $this->changePassword($id, $password);
        $admin->raw_password = $password;

        return $admin;
    }

}
