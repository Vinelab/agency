<?php namespace Agency\Cms\Repositories;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */

use Hash, Str;

use Agency\Cms\Admin;

class AdminRepository extends Repository implements Contracts\AdminRepositoryInterface {

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

        $admin->raw_password = $raw;

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
    protected function generatePassword()
    {
        return uniqid();
    }

    public function generateCode($email)
    {
        $code = Str::random($length = 64);
        $admin = $this->findBy('email',$email);
        $admin->code = $code;
        $admin->save();

        return $admin; 

    }

    public function changePassword($admin,$password)
    {
        $admin->password=Hash::make($password);
        return $admin->save();
    }

    public function updateProfile($admin, $input)
    {
        
        $admin->name = $input['name'];
        $admin->email = $input['email'];
        return $admin->save();

    }
}