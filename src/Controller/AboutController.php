<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {
        $params = [];

        if (!is_null($this->getUser())) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $params += ['user' => $this->getUser()];

        } else {
            $params += ['user' => null];
        }

        return $this->render('about/index.html.twig', [
            'params' => $params,
            'controller_name' => 'AboutController',
        ]);
    }
}
