<?php namespace Agency\Cms\Repositories\Contracts;

interface AdminRepositoryInterface {

	public function generateCode($email);

	public function changePassword($admin,$password);

	public function updateProfile($admin,$input);
    
}