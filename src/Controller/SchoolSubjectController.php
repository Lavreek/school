<?php

namespace App\Controller;

use App\Entity\SchoolSubjects;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SchoolSubjectController extends AbstractController
{
    const title = "Предметы";
    const form_class = "school-subject";
    const create_path = "/school/subject/form/create";

    #[Route('/school/subjects', name: 'app_school_subject')]
    public function index(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(SchoolSubjects::class)->findAll();

        $templates = [
            'title' => self::title,
            'table' => $table,
            'create_path' => self::create_path,
        ];

        return $this->render('patterns/table-view.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route(path: '/school/subject/form/create', name: 'app_SS_form_create')]
    public function schoolSubjectFormCreate(ManagerRegistry $registry): Response
    {

        $table = $registry->getRepository(SchoolSubjects::class)->findAll();

        $templates = ['title' => self::title, 'form_class' => self::form_class, 'table' => $table];

        return $this->render('patterns/form-create.html.twig', [
            'form_params' => [],
            'templates' => $templates,
        ]);
    }

    #[Route(path: '/school/subject/form/edit/{id}', name: 'app_school_subject_form')]
    public function schoolSubjectFormEdit(ManagerRegistry $registry, $id): Response
    {
        $params = &$this->params;

        $this->setUserParam($params);
        $params['school_subjects'] = $registry->getRepository(SchoolSubjects::class)->findAll();
        $schoolSubject = $registry->getRepository(SchoolSubjects::class)->findOneBy(['id' => $id]);

        $formParams = ['id' => $schoolSubject->getId(), 'title' => $schoolSubject->getTitle()];

        return $this->render('patterns/form-create.html.twig', [
            'form_params' => $formParams,
            'params' => $params,
        ]);
    }

    #[Route('/api/school/subject/create', name: 'app_school_subject_form')]
    public function schoolSubjectCreate(ManagerRegistry $registry, Request $request): JsonResponse
    {
        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();

            if (isset($postData['_title'], $postData['_user_id'])) {
                $title = addslashes($postData['_title']);
                $userId = addslashes($postData['_user_id']);

                $manager = $registry->getManager();
                $schoolSubject = new SchoolSubjects();

                $schoolSubject->setTitle($title);

                $manager->persist($schoolSubject);
                $manager->flush();

                return new JsonResponse(['success' => 'Row is added in db']);
            }
        }

        return new JsonResponse(['error' => 'Wrong params']);
    }
}
