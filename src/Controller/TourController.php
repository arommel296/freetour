<?php

namespace App\Controller;

use App\Event\EventoCancelaTour;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TourController{

    private $eventoTour;

    public function __construct(EventoCancelaTour $eventoTour) {
        $this->eventoTour = $eventoTour;
    }
    public function cancelaTour(){
        
    }
}