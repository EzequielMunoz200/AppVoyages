<?php
namespace App\Security;
//https://symfony.com/doc/current/security/user_checkers.html
//use App\Security\AccountDisabledException;
use App\Entity\User as AppUser;
//use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
            if (!$user->getIsActive()) {
                throw new CustomUserMessageAccountStatusException('Votre compte est désactivé.');       
            }
    }

    public function checkPostAuth(UserInterface $user)
    {
        
    }
}