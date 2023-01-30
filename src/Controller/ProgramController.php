<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Entity\Programms;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController
{
    const title = "Образовательная программа";
    const form_class = "program";
    const create_program_path = '/program/form/create';

    #[Route('/programs', name: 'app_program')]
    public function programView(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(Programms::class)->findAll();

        $templates = [
            'table' => $table
        ];

        return $this->renderProgram('program/view.html.twig', $templates);
    }

    #[Route('/program/form/create', name: 'app_program_form_create')]
    public function programFormCreate(ManagerRegistry $registry): Response
    {
        $group = $registry->getRepository(Groups::class)->findAll();

        $templates = [
            'group' => $group,
        ];

        return $this->renderProgram('program/program-form-create.html.twig', $templates);
    }

    #[Route('/api/program/create', name: 'api_program_create')]
    public function apiProgramCreate(ManagerRegistry $registry, Request $request): Response
    {
        if (count($request->request->all()) > 0) {
            $manager = $registry->getManager();
            $postData = $request->request->all();

            $program = new Programms();

            $program->setGroupId($postData['_group_select']);
            $program->setTitle($postData['_title']);

            $program->setStartedAt(new \DateTime($postData['_date_start']));
            $program->setEndedAt(new \DateTime($postData['_date_end']));

            $manager->persist($program);
            $manager->flush();

            return new JsonResponse(['success' => 'success']);
        }

        return new JsonResponse(['error' => 'Error']);
    }

    private function renderProgram($template, $templates) {
        $templates['title'] = self::title;
        $templates['create_program_path'] = self::create_program_path;
        $templates['form_class'] = self::form_class;

        return $this->render($template, [
            'templates' => $templates,
        ]);
    }
}
