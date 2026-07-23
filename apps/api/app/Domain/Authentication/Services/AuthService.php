<?php

namespace App\Domain\Authentication\Services;

use App\Domain\Authentication\Entities\User;
use Illuminate\Http\Request;

interface AuthService
{
    public function register(string $firstName, string $lastName, string $email, string $password): User;

    public function login(string $email, string $password, Request $request): array; // Returns ['user' => User, 'token' => string]

    public function refreshToken(string $token): string;

    public function logout(string $token): void;

    public function forgotPassword(string $email): void;

    public function resetPassword(string $token, string $password): void;

    public function verifyEmail(string $token, string $email): void;

    public function resendVerificationEmail(string $email): void;
}
