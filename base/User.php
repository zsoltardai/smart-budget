<?php

include_once 'AES.php';

class User
{
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $password_hash;

    function __construct(string $firstname, string $lastname, string $email, string $password_hash) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password_hash = $password_hash;
    }

    public function toJSON() : string {
        return json_encode($this);
    }

    public function setFirstname($value) : void {
        $this->firstname = $value;
    }

    public function setLastname($value) : void {
        $this->lastname = $value;
    }

    public static function fromJSON(string $json) : ?User {
        $object = json_decode($json, true);
        if (!array_key_exists('firstname', $object)) return null;
        if (!array_key_exists('lastname', $object)) return null;
        if (!array_key_exists('email', $object)) return null;
        if (!array_key_exists('password_hash', $object)) return null;
        return new User($object['firstname'], $object['lastname'], $object['email'], $object['password_hash']);
    }
}