<?php

namespace App\Domain\Authentication\DTO;

class LoginRequestDTO
{
    public string $email;

    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
