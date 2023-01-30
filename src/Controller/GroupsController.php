<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Groups;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupsController extends AbstractController
{
    const title = 'Группы';
    const form_class = 'groups';
    const create_path = '/groups/form/create';

    #[Route('/groups', name: 'app_groups_table')]
    public function getGroupsTable(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(Groups::class)->findAll();

        $templates = [
            'title' => self::title,
            'table' => $table,
            'create_path' => self::create_path,
        ];

        return $this->render('patterns/table-view.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/groups/form/create', name: 'app_groups_form_create')]
    public function groupFormCreate(ManagerRegistry $registry): Response
    {
        $table = $registry->getRepository(Courses::class)->findAll();

        $templates = [
            'title' => self::title,
            'create_path' => self::create_path,
            'form_class' => self::form_class,
        ];

        $form_params = [
            'title' => '',
            'course_select' => $table,
        ];

        return $this->render('patterns/form-create.html.twig', [
            'templates' => $templates,
            'form_params' => $form_params,
        ]);
    }

    #[Route('/api/form/group/create', name: 'api_form_group_create')]
    public function apiFormGroupCreate(ManagerRegistry $registry, Request $request): JsonResponse
    {
        $groupKeys = ['_title', '_course_select'];

        if (count($request->request->all()) > 0) {
            $postData = $request->request->all();

            foreach ($groupKeys as $key) {
                if (!isset($postData[$key])) {
                    return new JsonResponse(['error' => 'Too few arguments to execute method']);
                }
            }

            $manager = $registry->getManager();
            $group = new Groups();

            $group->setTitle(addslashes($postData['_title']));
            $group->setCourseId(addslashes($postData['_course_select']));

            $manager->persist($group);
            $manager->flush();

            return new JsonResponse(['success' => 'Group row added in db']);
        }

        return new JsonResponse(['error' => 'Empty request data']);
    }
}
