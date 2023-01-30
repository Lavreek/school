<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    const title = "Пользователи";
    const create_path = "/users/form/create";
    const form_class = "users";

    #[Route('/users', name: 'app_users_table')]
    public function getUsers(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(User::class)->findAll();

        $templates = [
            'title' => self::title,
            'table' => $table,
            'create_path' => self::create_path,
        ];

        return $this->render('user/users-table.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/users/form/create', name: 'app_users_form_create')]
    public function usersFormCreate(ManagerRegistry $registry): Response
    {

        $templates = [
            'title' => self::title,
            'form_class' => self::form_class,
            'password' => "123123123",
        ];

        return $this->render('user/users-form-create.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/api/users/create', name: 'api_users_create')]
    public function apiUsersCreate(
        ManagerRegistry $registry, Request $request, UserPasswordHasherInterface $passwordHasher
    ): JsonResponse
    {
        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();

            if (isset($postData['_email'], $postData['_password'], $postData['_role_select'])) {
                $manager = $registry->getManager();

                $email = addslashes($postData['_email']);
                $password = $postData['_password'];
                $role = $postData['_role_select'];

                $user = new User();

                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $password
                );

                $user->setEmail($email);
                $user->setIsVerified(1);
                $user->setPassword($hashedPassword);
                $user->setRoles($this->getRole($role));

                $manager->persist($user);
                $manager->flush();

                return new JsonResponse(['success' => 'User is created']);
            }
        }

        return new JsonResponse(['error' => 'Too few arguments to execute method']);
    }

    private function getRole($role) : array
    {
        switch ($role) {
            case 'admin':
                return ["ROLE_ADMIN"];

            case 'teacher':
                return ["ROLE_TEACHER"];

            case 'student':
                return ["ROLE_STUDENT"];

            default:
                return [];
        }
    }
}
