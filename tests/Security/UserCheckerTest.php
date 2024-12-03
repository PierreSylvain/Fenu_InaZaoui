<?php
// tests/Security/UserCheckerTest.php

namespace App\Tests\Security;

use App\Entity\User;
use App\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

class UserCheckerTest extends TestCase
{
    public function testCheckPreAuthWithUnrestrictedUser(): void
    {
        $user = new User();
        
        $checker = new UserChecker();
        $checker->checkPreAuth($user);
        
        // Affirmer qu'aucune exception n'est levée
        $this->addToAssertionCount(1);
    }

    public function testCheckPreAuthWithRestrictedUser(): void
    {
        $user = new User();
        $user->setRestricted(true);
        
        $checker = new UserChecker();
        
        // Affirmer qu'une exception CustomUserMessageAccountStatusException est levée
        $this->expectException(CustomUserMessageAccountStatusException::class);
        $this->expectExceptionMessage('Votre compte est restreint.');
        
        $checker->checkPreAuth($user);
    }
    
    public function testCheckPostAuthWithUnrestrictedUser(): void
    {
        $user = new User();
        
        $checker = new UserChecker();
        $checker->checkPostAuth($user);
        
        // Affirmer qu'aucune exception n'est levée
        $this->addToAssertionCount(1);
    }
    
    public function testCheckPostAuthWithRestrictedUser(): void
    {
        $user = new User();
        $user->setRestricted(true);
        
        $checker = new UserChecker();
        
        // Affirmer qu'une exception AccountExpiredException est levée
        $this->expectException(AccountExpiredException::class);
        $this->expectExceptionMessage('Votre compte est restreint');
        
        $checker->checkPostAuth($user);
    }
}