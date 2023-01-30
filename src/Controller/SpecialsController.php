<?php

namespace App\Controller;

use App\Entity\Departments;
use App\Entity\Specializations;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialsController extends AbstractController
{
    private array $params = [];

    private function setUserParam() {
        if (is_null($this->getUser())) {
            return $this->redirectToRoute('homepage');
        } else {
            $this->params += ['user' => $this->getUser()];
        }
    }

    #[Route('/department/{name}', name: 'app_specials')]
    public function index(ManagerRegistry $registry, $name): Response
    {
        $this->setUserParam();
        $this->params['title'] = $name;

        $department = $registry->getRepository(Departments::class)->findOneBy(['title' => $name]);

        $this->params['specializations'] = $registry->getRepository(Specializations::class)->findBy(['departament_id' => $department->getId()]);
        $this->params['departments'] = $department;

        return $this->render('specials/index.html.twig', [
            'params' => $this->params,
        ]);
    }

    #[Route('/specials/create/{department_id}', name: 'app_specials_create')]
    public function createSpecials(ManagerRegistry $registry, $department_id): Response
    {
        $this->setUserParam();
        $this->params['departments'] = ($registry->getRepository(Departments::class)->findBy(['id' => $department_id]))[0];
        $this->params['title'] = $this->params['departments']->getTitle();

        return $this->render('specials/create.html.twig', [
            'params' => $this->params,
        ]);
    }

    #[Route('/api/specials/create', name: 'api_specials_create', methods: 'POST')]
    public function apiCreateSpecials(ManagerRegistry $registry, Request $request): JsonResponse
    {
        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();

            if (isset($postData['_title'], $postData['_user_id'])) {
                $manager = $registry->getManager();
                $special = new Specializations();

                $title = addslashes($postData['_title']);
                $userId = addslashes($postData['_user_id']);
                $department_id = addslashes($postData['_department_id']);

                $special->setTitle($title);
                //$special->setUpdatedOn(new \DateTime());
                //$special->setUpdatedBy($userId);
                //$special->setCreatedOn(new \DateTime());
                //$special->setCreatedBy($userId);
                $special->setDepartamentId($department_id);

                $manager->persist($special);
                $manager->flush();

                return new JsonResponse(['success' => "Row added in db"]);
            }
        }

        return new JsonResponse(['error' => "Wrong params"]);
    }
}
