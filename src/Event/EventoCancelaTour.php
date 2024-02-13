<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class EventoCancelaTour extends Event{
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function getData(){
        return $this->data;
    }
}