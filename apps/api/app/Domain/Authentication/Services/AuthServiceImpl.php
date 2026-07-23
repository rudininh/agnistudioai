<?php

namespace App\Domain\Authentication\Services;

use App\Domain\Authentication\Entities\User;
use App\Domain\Authentication\Repositories\UserRepository;
use App\Domain\Authentication\ValueObjects\Email;
use App\Domain\Authentication\ValueObjects\Password;
use App\Events\LoginFailed;
use App\Events\LoginSuccess;
use App\Events\UserRegistered;
use App\Models\User as EloquentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthServiceImpl implements AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(string $firstName, string $lastName, string $email, string $password): User
    {
        // Check if email already exists
        $emailVo = new Email($email);
        if ($this->userRepository->existsByEmail($emailVo)) {
            throw new \InvalidArgumentException('Email already exists');
        }
        // Create user with plain text password - User entity will handle hashing via Password::fromPlainText()
        $user = new User($firstName, $lastName, $email, $password);
        $this->userRepository->save($user);
        // Fire the UserRegistered event
        event(new UserRegistered($user));

        return $user;
    }

    public function login(string $email, string $password, Request $request): array
    {
        // Find user by email using Eloquent model for token creation
        $eloquentUser = EloquentUser::where('email', $email)->first();

        if (! $eloquentUser) {
            // Fire login failed event for non-existent user
            event(new LoginFailed($email, $request));
            throw new \InvalidArgumentException('Invalid credentials');
        }

        // Get domain user for password check
        $user = $this->userRepository->findByEmail(new Email($email));

        if (! $user) {
            // This shouldn't happen if Eloquent user exists, but just in case
            event(new LoginFailed($email, $request));
            throw new \InvalidArgumentException('Invalid credentials');
        }

        // Check password using the User entity's authenticate method
        if (! $user->authenticate($password)) {
            // Fire login failed event for incorrect password
            event(new LoginFailed($email, $request));
            throw new \InvalidArgumentException('Invalid credentials');
        }

        // Fire login success event
        event(new LoginSuccess($user, $request));

        // Create a personal access token using Laravel Sanctum
        $accessToken = $eloquentUser->createToken('auth-token');

        return [
            'user' => $user,
            'token' => $accessToken->plainTextToken,
        ];
    }

    public function refreshToken(string $token): string
    {
        // In a real app, you would validate the token and issue a new one.
        // For simplicity, we're just generating a new token.
        // In production, you would implement proper refresh token rotation.
        return hash('sha256', Str::random(40));
    }

    public function logout(string $token): void
    {
        // Invalidate the token (e.g., remove from database or blacklist)
        // For now, we'll do nothing as this would typically be handled at the token storage level
    }

    public function forgotPassword(string $email): void
    {
        // Implementation would send a reset link
        // For now, we'll do nothing.
    }

    public function resetPassword(string $token, string $password): void
    {
        // Implementation would reset the password using the token
        // For now, we'll do nothing.
    }

    public function verifyEmail(string $email, string $token): void
    {
        // Implementation would verify the email
        // For now, we'll do nothing.
    }

    public function resendVerificationEmail(string $email): void
    {
        // Implementation would resend verification email
        // For now, we'll do nothing.
    }
}
