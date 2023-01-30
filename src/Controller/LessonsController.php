<?php

namespace App\Controller;

use App\Entity\Lessons;
use App\Entity\Programms;
use App\Entity\SchoolSubjects;
use App\Entity\Teacher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LessonsController extends AbstractController
{
    #[Route('/program/{id}/{name}/lessons', name: 'app_program_lessons')]
    public function index(ManagerRegistry $registry, $id, $name): Response
    {
        $table = $registry->getRepository(Lessons::class)->findBy(['program_id' => $id]);

        $templates = [
            'program_id' => $id,
            'title' => $name,
            'lessons' => $table,
        ];

        return $this->renderLessons('lessons/list-view.html.twig', $templates);
    }

    #[Route('/lesson/form/create/{id}', name: 'app_lessons_create')]
    public function lessonCreate(ManagerRegistry $registry, $id): Response
    {
        $table = $registry->getRepository(Lessons::class)->findBy(['program_id' => $id]);
        $teacher = $registry->getRepository(Teacher::class)->findAll();
        $subject = $registry->getRepository(SchoolSubjects::class)->findAll();

        $templates = [
            'id' => $id,
            'teacher' => $teacher,
            'subject' => $subject,
            'program_id' => $id,
            'title' => "Создание уроков",
            'lessons' => $table,
        ];

        return $this->renderLessons('lessons/lesson-create.html.twig', $templates);
    }

    #[Route('/api/lesson/create', name: 'api_lessons_create')]
    public function apiLessonCreate(ManagerRegistry $registry, Request $request): Response
    {
        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();
            $manager = $registry->getManager();

            $lesson = new Lessons();
            $lesson->setName($postData['_name']);
            $lesson->setDayOfWeek($postData['_date_of_week_select']);
            $lesson->setProgramId($postData['_program_id']);
            $lesson->setSchoolSubjectId($postData['_subject_select']);
            $lesson->setTeacherId($postData['_teacher_select']);
            $manager->persist($lesson);
            $manager->flush();

            return new JsonResponse(['success' => 'success']);
        }

        return new JsonResponse(['error' => 'error']);
    }

    #[Route('/api/get/lesson', name: 'api_lessons_create')]
    public function apiLessonGet(ManagerRegistry $registry, Request $request): Response
    {
        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();
            $dayofweek = date('w', strtotime($postData['date']. " 00:00:00"));

            $program = $registry->getRepository(Programms::class)->findBy(['group_id' => $postData['groupId']]);

            $lesson = $registry->getRepository(Lessons::class)->findByProgram($program[0]->getId(), $dayofweek);

            $lesArray = [];

            foreach ($lesson as $value) {
                array_push($lesArray, $value->getName());
            }

            return new JsonResponse(['success' => $lesArray, 'program' => $program[0]->getId(), 'dateofweek' => $dayofweek, 'date' => $postData['date']]);
        }

        return new JsonResponse(['error' => 'error']);
    }

    private function renderLessons($template, $templates) {
        $templates['lesson_create_path'] = '/lesson/form/create/';
        $templates['form_class'] = 'lesson';

        return $this->render($template, [
            'templates' => $templates,
        ]);
    }
}
