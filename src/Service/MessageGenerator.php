<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function getBajaReservaMessage(): string
    {
        $this->logger->info('Mensaje de reserva cancelada enviada.');
        return "El tour que había reservado ha sido cancelado";
    }
}