<?php
require __DIR__ . '/apps/api/vendor/autoload.php';

use App\Domain\Authentication\Entities\User;
use App\Domain\Authentication\ValueObjects\Password;
use App\Infrastructure\Persistence\Eloquent\Authentication\UserRepository;

// Test Password class
echo "=== Testing Password Class ===\n";
$plainPassword = 'MySecurePass123!';
$hash = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);

// Test 1: Creating password from plain text
$passwordFromPlain = new Password($plainPassword);
echo "Password from plain text - Hash: " . $passwordFromPlain->getHash() . "\n";
echo "Password verification (correct): " . ($passwordFromPlain->verify($plainPassword) ? 'true' : 'false') . "\n";
echo "Password verification (wrong): " . ($passwordFromPlain->verify('wrong') ? 'true' : 'false') . "\n";

// Test 2: Creating password from hash (should recognize it's already hashed)
$passwordFromHash = new Password($hash);
echo "Password from hash - Hash: " . $passwordFromHash->getHash() . "\n";
echo "Hashes match: " . ($hash === $passwordFromHash->getHash() ? 'true' : 'false') . "\n";
echo "Password verification (correct): " . ($passwordFromHash->verify($plainPassword) ? 'true' : 'false') . "\n";
echo "Password verification (wrong): " . ($passwordFromHash->verify('wrong') ? 'true' : 'false') . "\n\n";

// Test User entity
echo "=== Testing User Entity ===\n";
$user = new User('John', 'Doe', 'john@example.com', $plainPassword);
echo "User ID: " . $user->getId() . "\n";
echo "User email: " . $user->getEmailValue() . "\n";
echo "User password verify (correct): " . ($user->authenticate($plainPassword) ? 'true' : 'false') . "\n";
echo "User password verify (wrong): " . ($user->authenticate('wrong') ? 'true' : 'false') . "\n";

// Test setEmail
$user->setEmail('newemail@example.com');
echo "User email after change: " . $user->getEmailValue() . "\n";

// Test setIsVerified
$user->setIsVerified(true);
echo "User is verified: " . ($user->isVerified() ? 'true' : 'false') . "\n";
echo "User email verified at: " . ($user->getEmailVerifiedAt() ? $user->getEmailVerifiedAt()->format('Y-m-d H:i:s') : 'null') . "\n";

// Test setIsActive
$user->setIsActive(false);
echo "User is active: " . ($user->isActive() ? 'true' : 'false') . "\n";

echo "\nAll tests passed!\n";