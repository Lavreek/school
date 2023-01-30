<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeachersController extends AbstractController
{
    const title = "Учителя";
    const link_path = "/teachers/link";
    const form_class = 'teachers';

    #[Route('/teachers', name: 'app_teachers_table')]
    public function teachersTable(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(Teacher::class)->findTeacherWithUser();
        $templates = [
            'title' => self::title,
            'table' => $table,
            'edit_path' => "",
            'link_path' => self::link_path,
        ];

        return $this->render('teachers/teachers-table.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/teachers/link', name: 'app_teachers_link')]
    public function teachersLink(ManagerRegistry $registry): Response
    {
        $user = new User();
        $table = $registry->getRepository(User::class)->findUsersByRole('%ROLE_TEACHER%');
        $templates = [
            'title' => "Связь пользователей",
            'table' => $table,
            'link_path' => "/teachers/form/link/",
        ];

        return $this->render('teachers/teachers-table-link.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/teachers/form/edit/{id}', name: 'app_teachers_form_edit')]
    public function teachersFormEdit($id): Response
    {
        $templates = [
            'form_class' => self::form_class,
            'title' => self::title,
        ];

        return $this->render('teachers/teachers-table.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/teachers/form/link/{id}', name: 'app_teachers_form_link')]
    public function teachersFormLink(ManagerRegistry $registry, $id): Response
    {
        $user = $registry->getRepository(User::class)->find($id);

        $templates = [
            'user' => $user,
            'form_class' => 'teachers-link',
            'title' => self::title,
        ];

        return $this->render('teachers/teachers-form-link.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/api/teachers/link/create', name: 'api_teachers_form_link_create')]
    public function apiTeachersLinkCreate(ManagerRegistry $registry, Request $request): JsonResponse
    {
        if (count($request->request->all()) > 0 ) {
            $manager = $registry->getManager();
            $teacher = new Teacher();

            $postData = $request->request->all();

            $teacher->setName($postData['_teacher']);
            $teacher->setUserId($postData['_user_id']);

            $manager->persist($teacher);
            $manager->flush();

            return new JsonResponse(['success' => "Done"]);
        }

        return new JsonResponse(['error' => "Error"]);
    }
}
