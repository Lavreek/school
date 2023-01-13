<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $params = [];
        $role = null;

        try {
            if (!is_null($this->getUser())) {
                $user = $this->getUser();
                $role = $user->getRoles();

                if ($role != 'USER_GUEST')
                    return $this->redirectToRoute('homepage');
            } else {
                $params += ['user' => null];
            }

        } catch (\Exception $e) {
            return $this->redirectToRoute('homepage');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'user' => ['role' => $role],
            'last_username' => $lastUsername,
            'error'         => $error,
            'params' => $params,
        ]);
    }
}
