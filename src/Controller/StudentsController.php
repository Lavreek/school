<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Entity\Students;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentsController extends AbstractController
{
    const title = "Студенты";
    const edit_path = "/students/form/edit";
    const link_path = "/students/link";
    const form_class = 'students';

    #[Route('/students', name: 'app_students')]
    public function studentsTable(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(Students::class)->findStudentWithUser();

        $templates = [
            'title' => self::title,
            'edit_path' => self::edit_path,
            'link_path' => self::link_path,
            'table' => $table,
        ];

        return $this->render('students/students-table.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/students/link', name: 'app_students_link')]
    public function studentslink(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(User::class)->findUsersByRole('%ROLE_STUDENT%');

        $templates = [
            'title' => 'Связь студентов с пользователями',
            'link_path' => '/students/form/link/',
            'table' => $table,
        ];

        return $this->render('students/students-table-link.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/students/form/link/{id}', name: 'app_students_form_link')]
    public function studentsFormLink(ManagerRegistry $registry, $id): Response
    {
        $user = $registry->getRepository(User::class)->find($id);

        $group = $registry->getRepository(Groups::class)->findAll();

        $templates = [
            'user' => $user,
            'group' => $group,
            'form_class' => 'students-link',
            'title' => self::title,
        ];

        return $this->render('students/students-form-link.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/api/students/link/create', name: 'api_students_form_link_create')]
    public function apiTeachersLinkCreate(ManagerRegistry $registry, Request $request): JsonResponse
    {
        if (count($request->request->all()) > 0 ) {
            $manager = $registry->getManager();
            $student = new Students();

            $postData = $request->request->all();

            $student->setName($postData['_student']);
            $student->setUserId($postData['_user_id']);
            $student->setGroupId($postData['_group_select']);

            $manager->persist($student);
            $manager->flush();

            return new JsonResponse(['success' => "Done"]);
        }

        return new JsonResponse(['error' => "Error"]);
    }
}
