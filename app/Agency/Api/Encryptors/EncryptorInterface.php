<?php namespace Agency\Api\Encryptors;

interface EncryptorInterface {

    public function encrypt($key,$data);

}