<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;

class CorreoManager extends AbstractController
{
    public function __construct(
        private MessageGenerator $messageGenerator,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager,
        private string $adminEmail,
        private Pdf $knpSnappyPdf
    ) {
    }

    public function notifyOfSiteUpdate(): bool
    {
        $happyMessage = $this->messageGenerator->getBajaReservaMessage();

        $usuario=$this->entityManager->getRepository(Usuario::class)->findAll();
        $error = "";

        for ($i=1; $i < count($usuario); $i++) { 
                $email = (new Email())
                ->from($this->adminEmail)
                ->to($usuario[$i]->getEmail())
                ->subject('Cancelación de su reserva!')
                ->text('Le comunicamos lo siguiente: '.$happyMessage);

            $this->mailer->send($email);
            if ($i==count($usuario)-1){
                return true;
            }
        }

        return false;
        
    }

    public function sendEmail($correo, $subject, $text): bool
    {
        $email = (new Email())
        ->from($this->adminEmail)
        ->to($correo)
        ->subject($subject)
        ->text($text);

        $this->mailer->send($email);

        return false;
    }

    public function sendEmailPdf($correo, $subject, $text, $plantilla): bool
    {
        // Generación del pdf
        $pdf = $this->knpSnappyPdf->getOutputFromHtml($plantilla);

        // Guardado del pdf en un archivo temporal
        $pdfPath = tempnam(sys_get_temp_dir(), 'pdf_');
        file_put_contents($pdfPath, $pdf);

        // Creación del correo electrónico con el pdf adjunto
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($correo)
            ->subject($subject)
            ->text($text)
            ->attachFromPath($pdfPath, 'reserva.pdf'); //Adjunta el archivo temproal

        $this->mailer->send($email);

        // Eliminación del archivo temporal
        unlink($pdfPath);

        return true;
    }
}