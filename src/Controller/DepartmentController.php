<?php

namespace App\Controller;

use App\Entity\Departments;
use App\Entity\Faculties;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    #[Route('/department/form/{id}', name: 'app_department_form')]
    public function formDepartment(ManagerRegistry $registry, $id): Response
    {
        $params = [];

        if (!is_null($this->getUser())) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $params += ['user' => $this->getUser()];

        } else {
            return $this->redirectToRoute('homepage');
        }

        $faculty = $registry->getRepository(Faculties::class)->findBy(['id' => $id]);

        return $this->render('department/create.html.twig', [
            'faculty' => $faculty[0],
            'params' => $params,
        ]);
    }

    #[Route('/department/create', name: 'app_department_create', methods: 'POST')]
    public function createDepartment(ManagerRegistry $registry, Request $request): JsonResponse
    {
        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();

            if (isset($postData['_title'], $postData['_user_id'])) {
                $manager = $registry->getManager();
                $department = new Departments();

                $title = addslashes($postData['_title']);
                $userId = addslashes($postData['_user_id']);
                $faculty_id = addslashes($postData['_faculty_id']);

                $department->setTitle($title);
                $department->setUpdatedOn(new \DateTime());
                $department->setUpdatedBy($userId);
                $department->setCreatedOn(new \DateTime());
                $department->setCreatedBy($userId);
                $department->setFacultyId($faculty_id);

                $manager->persist($department);
                $manager->flush();

                return new JsonResponse(['success' => "Row added in db"]);
            }
        }

        return new JsonResponse(['error' => "Wrong params"]);
    }

    #[Route('/department', name: 'app_department')]
    public function index(): Response
    {
        return $this->render('department/index.html.twig', [
            'controller_name' => 'DepartmentController',
        ]);
    }
}
