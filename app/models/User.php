<?php

class User
{
    public $id;
    public $nom;
    public $email;
    public $password;
    public $role;
    public $birthdate;

    public function __construct($nom, $email, $password)
    {
        $this->nom = $nom;
        $this->email = $email;
        $this->password = $password;
    }
}

