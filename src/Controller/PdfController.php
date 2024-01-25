<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'app_pdf'), IsGranted('ROLE_USER', 'ROLE_ADMIN', 'ROLE_GUIA')]
    public function pdfAction(Pdf $knpSnappyPdf)
    {
        $html = $this->renderView('pdf/index.html.twig');

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'file.pdf'
        );
    }
}

// class PdfController extends AbstractController
// {
//     #[Route('/pdf', name: 'app_pdf')]
//     public function index(): Response
//     {
//         return $this->render('pdf/index.html.twig', [
//             'controller_name' => 'PdfController',
//         ]);
//     }
// }
