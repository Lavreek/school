<?php

namespace App\Twig\Runtime;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime extends AbstractController implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function doSomething($value)
    {
        // ...
    }

    public function templateGetUserAuth() {
        $user = $this->getUser();

        if (is_null($user)) {
            return $this->redirectToRoute('app_home');
        }

        return $user;
    }

    public function templateGetUserEmail() {
        $user = $this->getUser();

        if (!is_null($user)) {
            return $user->getEmail();
        }

        return;
    }

    public function templateGetUserId() {
        $user = $this->getUser();

        if (!is_null($user)) {
            return $user->getUserIdentifier();
        }

        return;
    }

    public function templateGetUserRole() {
        $user = $this->getUser();

        if (!is_null($user)) {
            return $user->getRoles();
        }

        return;
    }
}
