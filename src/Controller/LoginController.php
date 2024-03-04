<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //UserChecker verifica si el usuario está verificado correctamente o no
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        // $user = $this->getUser();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/afterLogin', name: 'afterLogin')]
    public function afterLogin(Request $request)
    {
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): RedirectResponse
    {
        return $this->redirectToRoute('app_login');
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
