
<?php

namespace App\Domain\Authentication\Services;

use App\Domain\Authentication\Entities\User;
use App\Domain\Authentication\ValueObjects\Email;
use App\Domain\Authentication\ValueObjects\Password;

interface AuthService
{
    public function register(string \, string \, string \, string \): User;
    
    public function login(string \, string \): array; // Returns ['user' => User, 'token' => string]
    
    public function refreshToken(string \): string;
    
    public function logout(string \): void;
    
    public function forgotPassword(string \): void;
    
    public function resetPassword(string \, string \): void;
    
    public function verifyEmail(string \, string \): void;
    
    public function resendVerificationEmail(string \): void;
}