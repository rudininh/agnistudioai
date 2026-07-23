<?php

namespace App\Domain\Authentication\DTO;

class RefreshTokenRequestDTO
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
