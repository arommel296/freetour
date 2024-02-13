<?php

namespace App\Controller\Admin;

use App\Entity\Informe;
use App\Entity\Item;
use App\Entity\Reserva;
use App\Entity\Tour;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use App\Entity\Ruta;
use App\Entity\Valoracion;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;

class DashboardController extends AbstractDashboardController
{
    // private Usuario $usuario;

    // public function __construct(Usuario $usuario){
    //     $this->usuario = $usuario;
    // }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Freetour');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
            // ->update(Crud::PAGE_INDEX, '', '');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('General');
        yield MenuItem::linkToRoute('Página Principal', 'fa fa-home', 'principal');
        yield MenuItem::linkToCrud('Usuarios', 'fas fa-users', Usuario::class);
        yield MenuItem::linkToRoute('Calendario', 'fa fa-calendar', 'calendario');
        // yield MenuItem::linkToLogout('Cerrar Sesión', 'fa fa-sign-out');
        yield MenuItem::section('FreeTour');
        // yield MenuItem::linkToRoute('Rutas', 'fas fa-map-marked-alt', 'creaRuta');
        yield MenuItem::linkToCrud('Rutas', 'fas fa-map-marked-alt', Ruta::class);
        yield MenuItem::linkToCrud('Tours', 'fas fa-shoe-prints', Tour::class);
        yield MenuItem::linkToCrud('Items', 'fas fa-archway', Item::class);
        yield MenuItem::linkToCrud('Reservas', 'fas fa-bookmark', Reserva::class);
        yield MenuItem::linkToCrud('Informes', 'fas fa-flag', Informe::class);
        yield MenuItem::linkToCrud('Valoraciones', 'fas fa-magnifying-glass-location', Valoracion::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user);
    }

}
