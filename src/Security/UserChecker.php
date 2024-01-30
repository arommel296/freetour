<?php
namespace App\Security;

use App\Entity\Usuario as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserChecker implements UserCheckerInterface //extends AbstractController
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // if (!$user->isVerified()) {
        //     throw new CustomUserMessageAccountStatusException('Tu cuenta no está verificada. Revisa tu correo electrónico.');
        // }
        // else {
        //     $this->redirectToRoute('app_register');
        //     return;
        // }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // if ($user->isVerified()) {
        //     // throw new CustomUserMessageAccountStatusException('Tu cuenta no está verificada. Revisa tu correo electrónico.');
        //     $this->redirectToRoute('app_register');
        //     return;
        // }
    }
}