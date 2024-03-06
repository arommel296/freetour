<?php

namespace App\Service;

use Knp\Snappy\Pdf;

class GeneraPdfService
{
    private $snappy;

    public function __construct(Pdf $snappy)
    {
        $this->snappy = $snappy;
    }
    public function generaPdf($plantilla){
        $this->snappy->getOutputFromHtml($plantilla);
    }
}