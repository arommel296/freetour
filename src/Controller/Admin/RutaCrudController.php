<?php

namespace App\Controller\Admin;

use App\Entity\Ruta;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RutaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ruta::class;
    }

    #[Route('/creaRuta', name: 'creaRuta')]
    public function nuevaRuta(): Response
    {
        return $this->render('ruta/nuevaRuta.html.twig');
    }    
}    


