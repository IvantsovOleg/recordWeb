<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 09.11.2018
 * Time: 10:47
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;

class AppController extends Controller
{
    // Список специальностей
    const GET_SPECIALITY = 'KIOSK_SPEC_LIST';

    // Список врачей
    const GET_DOCTOR = 'KIOSK_DOC_LIST';

    // Доступные талоны
    const GET_RNUMB = 'KIOSK_RNUMB_LIST';

    // Блокирование выбранного талона перед записью
    const RNUMB_BLSTATUS = 'KIOSK_SET_BL_STATUS';

    // Проверка пациента
    const CHECK_PATIENT = 'KIOSK_CHECK_PATIENT';
    const CHECK_PATIENT_FOR_SCANNER = 'KIOSK_CHECK_PATIENT_FOR_SCANNER';

    // Запись пациента на прием
    const SUCCESS_APPT = 'KIOSK_APPT_COMPLETE';


    // Талоноы пациента
    const PATIENT_NUMBS_BY_ID = 'KIOSK_PATIENT_NUMBS_BY_ID';

    // Информация по выбранному талону (для печати)
    const RNUMB_INFO = 'KIOSK_RNUMB_INFO';

    // Отмена записи и блокировки на талон
    const RNUMB_CLEAR_STATUS = 'KIOSK_DELETE_RNUMB';

    const ELECTRONICQUEUE_GET_NEW_TALON = 'ELECTRONICQUEUE_GET_NEW_TALON';

    // Календарь врача
    const CALENDAR = 'KIOSK_GET_CALENDAR';

    public $layout = 'kioskWeb';

    /**
     * Глобальные правила
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Глобальные actions
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    function mb_ucfirst($str)
    {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));

        return $fc . mb_substr($str, 1);
    }

    /**
     * Авторизация пациента по фио дате рождения
     *
     * @param $auth
     *
     * @return array|bool
     */
    protected function checkUser($auth)
    {
        $params = [
            'p_last_name' => $auth->lastname,
            'p_first_name' => $auth->firstname,
            'p_second_name' => $auth->secondname,
            'p_dob' => $auth->dob,
            'p_phone' => $auth->phone,
        ];

        try {
            return $this->runXMLCommand(AppController::CHECK_PATIENT, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Отправка и получение результата от XML Server
     *
     * @param $command
     * @param array $params
     *
     * @return array|bool
     * @throws HttpException
     */
    protected function runXMLCommand($command, $params = [])
    {
        $result = [];
        $errorMsg = null;

        $paramList = $this->setParams($params);

        Yii::$app->xml->makeRequest($command, $paramList, $result, $errorMsg);

        if (isset($errorMsg)) {
            throw new HttpException(500, $errorMsg);
        }

        if (is_array($result) && count($result) > 0) {
            return $result;
        } else {
            return [];
        }
    }

    /**
     * Установка параметров для выполнения процедуры
     *
     * @param array $params
     *
     * @return array
     */
    private function setParams($params = [])
    {
        $result = [];
        if (is_array($params) && count($params) > 0) {
            foreach ($params as $key => $value) {
                array_push($result, ["NAME" => $key, "VALUE" => $value]);
            }
        }

        return $result;
    }

    protected function checkUserForScanner($auth)
    {
        $params = [
            'p_police' => $auth->police,
            'p_last_name' => $auth->lastname,
            'p_first_name' => $auth->firstname,
            'p_second_name' => $auth->secondname,
            'p_dob' => $auth->dob,
            'p_phone' => $auth->phone,
        ];

        try {
            return $this->runXMLCommand(AppController::CHECK_PATIENT_FOR_SCANNER, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Получение специальностей через XML сервер
     *
     * @param $patientId
     * @param $structCode
     *
     * @return array|bool
     */
    protected function getSpecialityList($patientId, $structCode)
    {
        $params = [
            'P_PATIENT_ID' => $patientId,
            'P_STRUCTCODE' => $structCode
        ];
        try {
            return $this->runXMLCommand(AppController::GET_SPECIALITY, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Получение списка врачей для переданной специальности через XML сервер
     *
     * @param $specId
     *
     * @param $patientId
     * @param $structCode
     *
     * @return array|bool
     */
    protected function getDoctorList($specId, $patientId, $structCode)
    {
        $params = [
            'P_PATIENT_ID' => $patientId,
            'P_SPEC_ID' => $specId,
            'P_STRUCTCODE' => $structCode
        ];

        try {
            return $this->runXMLCommand(AppController::GET_DOCTOR, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Получение списка талонов по специальности и врачу через XML сервер
     *
     * @param $specId
     * @param $docId
     *
     * @return array|bool
     */
    protected function getRnumbs($specId, $docId)
    {
        $params = ['P_SPEC_ID' => $specId, 'P_DOC_ID' => $docId,];

        try {
            return $this->runXMLCommand(AppController::GET_RNUMB, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Блокировка талона
     *
     * @param $rnumbId
     *
     * @return array|bool
     */
    protected function setBlockRnumb($rnumbId)
    {
        $params = ['P_RNUMB_ID' => $rnumbId];

        try {
            return $this->runXMLCommand(AppController::RNUMB_BLSTATUS, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Подтверждение записи на прием
     *
     * @param $patientId
     * @param $rnumbId
     *
     * @return array|bool
     */
    protected function complete($patientId, $rnumbId)
    {
        $params = ['P_RNUMB_ID' => $rnumbId, 'P_PATIENT_ID' => $patientId];

        try {
            return $this->runXMLCommand(AppController::SUCCESS_APPT, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Список талонов для указанного пациента
     *
     * @param $patientId
     *
     * @return array|bool
     */
    protected function getRnumbsByPatient($patientId)
    {
        $params = ['PATIENT_ID' => $patientId,];

        try {
            return $this->runXMLCommand(AppController::PATIENT_NUMBS_BY_ID, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Получить рассписание врача
     *
     * @param $specId
     * @param $docId
     * @param $datBgn
     * @param $datEnd
     *
     * @return array|bool
     */
    protected function getCalendar($specId, $docId, $datBgn, $datEnd)
    {
        $params = [
            'P_SPEC_ID' => $specId,
            'P_DOC_ID' => $docId,
            'P_FIRST_DATE' => $datBgn,
            'P_LAST_DATE' => $datEnd,
        ];

        try {
            return $this->runXMLCommand(AppController::CALENDAR, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Вернуть информацию по талону
     *
     * @param $rnumbId
     *
     * @return array|bool
     */
    protected function getRnumbInfo($rnumbId)
    {
        $params = [
            'P_RNUMB_ID' => $rnumbId
        ];

        try {
            return $this->runXMLCommand(AppController::RNUMB_INFO, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }

    /**
     * Отменить запись на талон и снятие блокировки
     *
     * @param $rnumbId
     *
     * @return array|bool
     */
    protected function deleteRnumb($rnumbId)
    {
        $params = [
            'P_RNUMB_ID' => $rnumbId
        ];

        try {
            return $this->runXMLCommand(AppController::RNUMB_CLEAR_STATUS, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }
	
	 protected function getNewTalon($queueId, $patientId)
    {
        $params = [
			'P_DEVICE_ID' => '',
            'P_QUEUE_ID' => $queueId,
			'P_PATIENT_ID' => $patientId
        ];

        try {
            return $this->runXMLCommand(AppController::ELECTRONICQUEUE_GET_NEW_TALON, $params);
        } catch (HttpException $e) {
            die($e);
        }
    }
}