<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Specializations;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourcesController extends AbstractController
{
    private array $params = [];

    const title = "Курсы";
    const form_class = "courses";
    const create_path = "/courses/form/create";

    private function setUserParam() {
        if (is_null($this->getUser())) {
            return $this->redirectToRoute('homepage');
        } else {
            $this->params += ['user' => $this->getUser()];
        }
    }

    #[Route('/courses', name: 'app_courses_table')]
    public function getCoursesTable(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(Courses::class)->findAll();

        $templates = [
            'title' => self::title,
            'create_path' => self::create_path,
            'table' => $table,
        ];

        return $this->render('patterns/table-view.html.twig', [
            'templates' => $templates
        ]);
    }

    #[Route('{name}/cources', name: 'app_cources')]
    public function index(ManagerRegistry $registry, $name): Response
    {
        $this->setUserParam();

        $this->params['title'] = $name;
        $special = $registry->getRepository(Specializations::class)->findOneBy(['title' => $name]);

        $this->params['specializations'] = $special;
        $this->params['cources'] = $registry->getRepository(Courses::class)->findBy(['specialization_id' => $special->getId()]);

        return $this->render('cources/index.html.twig', [
            'params' => $this->params,
        ]);
    }

    #[Route('/courses/form/create', name: 'app_courses_form_create')]
    public function formCreateCource(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(Specializations::class)->findAll();

        $form_params = [
            'title' => "",
            'special_select' => $table,
        ];

        $templates = [
            'title' => self::title,
            'form_class' => self::form_class,
        ];

        return $this->render('patterns/form-create.html.twig', [
            'templates' => $templates,
            'form_params' => $form_params,
        ]);
    }



    #[Route('/course/create/{id}', name: 'app_courses_create')]
    public function createCource(ManagerRegistry $registry, $id): Response
    {
        $this->setUserParam();

        $this->params['title'] = $id;
        $special = $registry->getRepository(Specializations::class)->findOneBy(['id' => $id]);

        $this->params['specializations'] = $special;
        $this->params['cources'] = $registry->getRepository(Courses::class)->findBy(['specialization_id' => $special->getId()]);

        return $this->render('cources/create.html.twig', [
            'params' => $this->params,
        ]);
    }

    #[Route('/api/course/create', name: 'api_courses_create')]
    public function apiCreateCource(ManagerRegistry $registry, Request $request): JsonResponse
    {
        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();

            if (isset($postData['_title'], $postData['_user_id'])) {
                $title = addslashes($postData['_title']);
                $userId = addslashes($postData['_user_id']);
                $specialization_id = addslashes(($postData['_specialization_id']));

                $manager = $registry->getManager();
                $cource = new Courses();

                $cource->setTitle($title);
                $cource->setSpecializationId($specialization_id);

                $manager->persist($cource);
                $manager->flush();

                return new JsonResponse(['success' => 'Row is added in db']);
            }
        }

        return new JsonResponse(['error' => 'Wrong params']);
    }

    #[Route('/api/form/course/create', name: 'api_form_course_create')]
    public function apiFormCreateCource(ManagerRegistry $registry, Request $request): JsonResponse
    {
        $courseKeys = ['_title', '_user_id', '_special_select'];

        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();


            foreach ($courseKeys as $key) {
                if (!isset($postData[$key])) {
                    return new JsonResponse(['error' => 'Too few arguments to execute method.']);
                }
            }

            $title = addslashes($postData['_title']);
            $specialization_id = addslashes(($postData['_special_select']));

            $manager = $registry->getManager();
            $course = new Courses();

            $course->setTitle($title);
            $course->setSpecializationId($specialization_id);

            $manager->persist($course);
            $manager->flush();

            return new JsonResponse(['success' => 'Row is added in db']);
        }

        return new JsonResponse(['error' => 'Wrong params']);
    }
}
