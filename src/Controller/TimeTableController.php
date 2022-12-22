<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TimeTableController extends AbstractController
{
    #[Route('/timetable', name: 'app_time_table')]
    public function index(): Response
    {
        $calendarArray = ['calendar_first', 'calendar_second', 'calendar_third', 'calendar_fourth', 'calendar_fifth',
            'calendar_sixth', 'calendar_seventh', 'calendar_eigth', 'calendar_ninth', 'calendar_tenth', 'calendar_eleventh',
            'calendar_twelfth',
        ];
        return $this->render('controls/time-table.html.twig', [
            'calendars' => $calendarArray,
            'controller_name' => 'TimeTableController',
        ]);
    }
}
