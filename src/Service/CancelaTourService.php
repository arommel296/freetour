<?php

namespace App\Service;

use Doctrine\Migrations\EventDispatcher;

class CancelaTourService{
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function triggerCancelaTour(){
        
    }
}