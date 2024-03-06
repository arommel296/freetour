<?php

namespace App\Service;

use App\Entity\Tour;
use App\Event\EventoCancelaTour;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CancelaTourService{
    private $eventDispatcher;
    private $correoManager;

    public function __construct(EventDispatcher $eventDispatcher, CorreoManager $correoManager) {
        $this->eventDispatcher = $eventDispatcher;
        $this->correoManager = $correoManager;
    }

    public function triggerCancelaTour(Tour $tour)
    {
        $evento = new EventoCancelaTour($tour);
        $this->eventDispatcher->dispatch($evento);
    }
}