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
            EventoCancelaTour::NAME => 'onTourDisponible',
        ];
    }

    // public function onTourDisponible(EventoCancelaTour $event)
    // {
    //     $tour = $event->getTour();

    //     if (!$tour->isDisponible() && count($tour->getReservas()) > 0) {
    //         $this->correoManager->notifyOfSiteUpdate();
    //     }
    // }

    // public function onTourDisponible(EventoCancelaTour $event)
    // {
    //     $reservas = $event->getTour()->getReservas();

    //     // if (!$tour->isDisponible() && count($tour->getReservas()) > 0) {
    //     //     $this->correoManager->sendEmail("hola@gmail.com", "Tour cancelado", "el tour que tenía reservado ha sido cancelado");
    //     // }
    //     foreach ($reservas as $reserva) {
    //         $this->correoManager->sendEmail($reserva->getUsuario()->getEmail(), "Tour cancelado", "El tour que tenía reservado ha sido cancelado");
    //     }
    // }
}