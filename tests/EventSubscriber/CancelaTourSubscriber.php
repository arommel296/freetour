<?php

namespace App\EventSubscriber;

use App\Event\EventoCancelaTour;
use App\Service\CorreoManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class CancelaTourSubscriber implements EventSubscriberInterface
{
    private $correoManager;

    public function __construct(CorreoManager $correoManager)
    {
        $this->correoManager = $correoManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            EventoCancelaTour::class => 'onTourDisponible',
        ];
    }

}