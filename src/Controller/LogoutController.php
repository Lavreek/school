<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function index(Security $security, LogoutEvent $event)
    {
        $response = $security->logout();

        $response = new RedirectResponse(
            $this->urlGenerator->generate('homepage'),
            RedirectResponse::HTTP_SEE_OTHER
        );

        $event->setResponse($response);
//        return $this->render('logout/index.html.twig', [
//            'controller_name' => 'LogoutController',
//        ]);
    }
}
