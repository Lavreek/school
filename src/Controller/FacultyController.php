<?php

namespace App\Controller;

use App\Entity\Departments;
use App\Entity\Faculties;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FacultyController extends AbstractController
{
    #[Route('/faculty/get', name: 'app_faculty_get', methods: 'POST')]
    public function index(ManagerRegistry $registry): JsonResponse
    {
        $resource = $registry->getRepository(Faculties::class)->findAll();

        return $this->json([
            'message' => 'Success!',
            'count' => count($resource),
            'resources' => $resource,
        ]);
    }

    #[Route('/faculty/create', name: 'app_faculty_create', methods: 'POST')]
    public function create(ManagerRegistry $registry, Request $request): JsonResponse
    {
        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();

            if (isset($postData['_title'], $postData['_user_id'])) {
                $title = addslashes($postData['_title']);
                $userId = addslashes($postData['_user_id']);

                $manager = $registry->getManager();
                $faculty = new Faculties();

                $faculty->setTitle($title);
                $faculty->setCreatedBy($userId);
                $faculty->setCreatedOn(new \DateTime());
                $faculty->setUpdatedBy($userId);
                $faculty->setUpdatedOn(new \DateTime());

                $manager->persist($faculty);
                $manager->flush();

                return new JsonResponse(['success' => 'Row is added in db']);
            }
        }

        return new JsonResponse(['error' => 'Wrong params']);
    }


    #[Route('/faculty/form', name: 'app_faculty_form')]
    public function form(): Response
    {
        $params = [];

        if (!is_null($this->getUser())) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $params += ['user' => $this->getUser()];

        } else {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('faculty/form.html.twig', [
            'params' => $params,
        ]);
    }

    #[Route('/faculty/open/{name}', name: 'app_faculty_open')]
    public function open(ManagerRegistry $registry, $name): Response
    {
        $params = [];

        if (!is_null($this->getUser())) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $params += ['user' => $this->getUser()];

        } else {
            return $this->redirectToRoute('homepage');
        }

        $faculty = $registry->getRepository(Faculties::class)->findBy(['title' => $name]);
        $departments = $registry->getRepository(Departments::class)->findBy(['faculty_id' => $faculty[0]->getId()]);

        return $this->render('faculty/open.html.twig', [
            'faculty' => $faculty[0],
            'departments' => $departments,
            'params' => $params,
        ]);
    }
}
