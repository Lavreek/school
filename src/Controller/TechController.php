<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechController extends AbstractController
{
    #[Route('/tech', name: 'app_tech')]
    public function index(): Response
    {
        $params = [];

        if (!is_null($this->getUser())) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $params += ['user' => $this->getUser()];

        } else {
            $params += ['user' => null];
        }

        return $this->render('tech/index.html.twig', [
            'controller_name' => 'TechController',
            'params' => $params,
        ]);
    }
}
