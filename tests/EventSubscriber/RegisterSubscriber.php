<?php

use App\Entity\Usuario;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
// for Doctrine < 2.4: use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use AppBundle\Entity\Product;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RegisterSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public static function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
        );
    }

    public function prePersist(PrePersistEventArgs $args)
    {
        // $this->index($args);
        $entity = $args->getObject();
        $nombre = $entity->getFullName();
        try {
            $this->sendEmail("Se ha registrado el usuario: $nombre");
        } catch (\Throwable $th) {
            
        }
        
    }

    public function index(PrePersistEventArgs $args)
    {
        $entity = $args->getObject();
        $nombre = $entity->getFullName();

        // if ($entity instanceof Usuario) {
        //     $entityManager = $args->getObjectManager();
        //     $this->sendEmail("Se ha registrado el usuario: $nombre");
        // }
        $this->sendEmail("Se ha registrado el usuario: $nombre");
    }

    private function sendEmail($message)
    {
        // Implementa la lógica para enviar el correo electrónico utilizando el servicio Mailer
        $email = (new Email())
            ->from('noreply@example.com')
            ->to('admin@example.com')
            ->subject('Nuevo Registro')
            ->text($message);

        $this->mailer->send($email);
    }
}