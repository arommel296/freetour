<?php

namespace App\Event;

use App\Entity\Tour;
use App\Service\CorreoManager;
use Symfony\Contracts\EventDispatcher\Event;

class EventoCancelaTour extends Event{
    // public const NAME = 'tour.disponible';
    private $tour;
    private $correoManager;

    public function __construct(Tour $tour, CorreoManager $correoManager)
    {
        $this->tour = $tour;
        $this->correoManager = $correoManager;
    }
    public function getTour()
    {
        return $this->tour;
    }

    public function onTourDisponible(EventoCancelaTour $event)
    {
        $tour = $event->getTour();

        if (!$tour->isDisponible() && count($tour->getReservas()) > 0) {
            $this->correoManager->sendEmail("hola@gmail.com", "Tour cancelado", "el tour que tenía reservado ha sido cancelado");
        }
    }
}