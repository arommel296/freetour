<?php

namespace App\Controller;

use App\Event\EventoCancelaTour;
use App\EventListener\TourListener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TourController{

    // private $eventoTour;

    // public function __construct(EventoCancelaTour $eventoTour) {
    //     $this->eventoTour = $eventoTour;
    // }
    // public function cancelaTour(){
    //     $listener = new TourListener();
    //     $listener->onPreUpdate($this->eventoTour);
    // }
}