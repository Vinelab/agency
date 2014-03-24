<?php namespace Agency\Api\Encryptors;

class Encryptor implements EncryptorInterface {

    public function encrypt($key,$data)
    {
       return hash_hmac("sha256", $data, $key);
    }

}