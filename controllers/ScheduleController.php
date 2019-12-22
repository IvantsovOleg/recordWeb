<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 18.03.2019
 * Time: 11:20
 */

namespace app\controllers;


use Yii;
use yii2fullcalendar\models\Event;

class ScheduleController extends AppController
{
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $session->set('isHome', '1');

        $btns = [
            [
                'title' => 'Взрослая',
                'struct_code' => '001001001',
                'url' => 'schedule/speciality'
            ],
            [
                'title' => 'Детская',
                'struct_code' => '001001002',
                'url' => 'schedule/speciality'
            ]
        ];

        return $this->render('index', compact('btns'));
    }

    /**
     * Список доступных специальностей
     * @return string
     */
    public function actionSpeciality($structCode)
    {
        $speciality = $this->getSpecialityList(null, $structCode);
        $url = 'schedule/doctor';
        return $this->render('speciality', compact('url', 'speciality', 'structCode'));
    }

    /**
     * Список доступных врачей по специальности
     * @param $specid
     * @param $specname
     * @return string
     */
    public function actionDoctor($specid, $specname, $structCode)
    {
        $session = Yii::$app->session;
        $session->set('speciality', ['specId' => $specid, 'specName' => $specname]);
        $doctors = $this->getDoctorList($specid, null, $structCode);
        return $this->render('doctor', compact('doctors', 'specid', 'specname', 'structCode'));
    }

    public function actionCalendar($specid, $specname, $docid, $docname)
    {
        $startDate = date('Y-m-d h:i:s');
        $endDate = date('Y-m-d h:i:s', $this->add_month(strtotime($startDate)));

        $calendar = $this->getCalendar($specid, $docid, $startDate, $endDate);

        $events = [];

        if (isset($calendar) && count($calendar) > 0) {

            foreach ($calendar as $key => $item) {
                $Event = new Event();
                $Event->id = $key;
                $Event->title = $item['dat_bgn'] . ' - ' . $item['dat_end'];
                $Event->start = date('Y-m-d\TH:i:s\Z', strtotime($item['talon_date'] . ' ' . $item['dat_bgn']));
                $Event->end = date('Y-m-d\TH:i:s\Z', strtotime($item['talon_date'] . ' ' . $item['dat_end']));
                $events[] = $Event;
            }

        }

        return $this->render('calendar', compact('docname', 'specname', 'events', 'calendar'));
    }


    public function add_month($time)
    {
        $d = date('j', $time);  // день
        $m = date('n', $time);  // месяц
        $y = date('Y', $time);  // год

        // Прибавить месяц
        $m++;
        if ($m > 12) {
            $y++;
            $m = 1;
        }

        // Это последний день месяца?
        if ($d == date('t', $time)) {
            $d = 31;
        }
        // Открутить дату до последнего дня месяца
        if (!checkdate($m, $d, $y)) {
            $d = date('t', mktime(0, 0, 0, $m, 1, $y));
        }
        // Вернуть новую дату в TIMESTAMP
        return mktime(0, 0, 0, $m, $d, $y);
    }

}