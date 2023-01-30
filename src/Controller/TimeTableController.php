<?php

namespace App\Controller;

use App\Entity\Groups;
use App\Entity\Students;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TimeTableController extends AbstractController
{
    #[Route('/timetable', name: 'app_time_table')]
    public function index(ManagerRegistry $registry): Response
    {
        $calendarArray = ['calendar_first', 'calendar_second', 'calendar_third', 'calendar_fourth', 'calendar_fifth',
            'calendar_sixth', 'calendar_seventh', 'calendar_eigth', 'calendar_ninth', 'calendar_tenth', 'calendar_eleventh',
            'calendar_twelfth',
        ];
        $user = $this->getUser();
        $group = $registry->getRepository(Students::class)->findBy(['user_id' => $user->getId()]);

        return $this->render('controls/time-table.html.twig', [
            'group' => $group,
            'calendars' => $calendarArray,
            'controller_name' => 'Расписание занятий',
        ]);
    }
}
