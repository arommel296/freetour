<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;

class CorreoManager extends AbstractController
{
    public function __construct(
        private MessageGenerator $messageGenerator,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager,
        private string $adminEmail
    ) {
    }

    public function notifyOfSiteUpdate(): bool
    {
        $happyMessage = $this->messageGenerator->getBajaReservaMessage();

        $usuario=$this->entityManager->getRepository(Usuario::class)->findAll();
        $error = "";

        for ($i=1; $i < count($usuario); $i++) { 
                $email = (new Email())
                ->from($this->adminEmail)
                ->to($usuario[$i]->getEmail())
                ->subject('Cancelación de su reserva!')
                ->text('Le comunicamos lo siguiente: '.$happyMessage);

            $this->mailer->send($email);
            if ($i==count($usuario)-1){
                return true;
            }
        }

        return false;
        
    }
}