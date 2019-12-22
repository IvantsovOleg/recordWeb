<?php

namespace app\controllers;

use Yii;
use app\models\Auth;
use yii\web\Response;
use Picqer\Barcode\BarcodeGeneratorSVG;

class SiteController extends AppController
{
    /**
     * Главная страница
     * @return string
     */
    public function actionIndex()
    {

        $session = Yii::$app->session;
        $session->removeAll();
        $session->set('isHome', '1');

        $model = new Auth();

        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {

            $user = $this->checkUser($model);
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (isset($user) && is_array($user) && isset($user[0]['keyid']) && $user[0]['keyid'] != 0) {

                $lastName = $this->mb_ucfirst(mb_strtolower($model->lastname));
                $firstName = $this->mb_ucfirst(mb_strtolower($model->firstname));
                $secondName = $this->mb_ucfirst(mb_strtolower($model->secondname));

                $patientId = $user[0]['keyid'];
                $patientPhone = $model->phone;
                $patientDob = $model->dob;

                $patientFullName = $lastName . ' ' . $firstName . ' ' . $secondName;
                $patientShortName = $lastName . ' ' . mb_substr($firstName, 0, 1) . '. ' . mb_substr($secondName, 0, 1) . '.';

                $session->set('patient', [
                    'id' => $patientId,
                    'fullName' => $patientFullName,
                    'shortName' => $patientShortName,
                    'dob' => $patientDob,
                    'phone' => $patientPhone
                ]);

                return ['success' => true, 'user' => $user[0]];

            } else {
                return ['success' => false, 'error' => $user[0]];
            }
        }
        return $this->render('index', compact('model'));
    }

    public function actionMain()
    {
        $session = Yii::$app->session;
        $patient = $session->get('patient');
        $btns = [
            [
                'title' => 'Записаться на прием',
                'url' => 'record/index',
				'type' => 'record'
            ],
//			[
//                'title' => 'НЕОТЛОЖНАЯ ПОМОЩЬ',
//                'url' => 'site/queue',
//				'queueId' => 1,
//				'type' => 'queue'
//            ],
//			[
//                'title' => 'Врачебная комиссия',
//                'url' => 'site/queue',
//				'queueId' => 2,
//				'type' => 'queue'
//            ],
//			[
//                'title' => 'ЗАБОР КРОВИ',
//                'url' => 'site/queue',
//				'queueId' => 3,
//				'type' => 'queue'
//            ],
            [
                'title' => 'Мои талоны',
                'url' => 'cancel/index',
				'type' => 'record'
            ]
        ];

        return $this->render('main', compact('btns', 'patient'));
    }

    /**
     * Авторизация пациента для просмотра талонов и/или записи на прием
     * @return array|string
     */
    public function actionAuth()
    {
        $session = Yii::$app->session;
        $session->set('isHome', '1');

        $model = new Auth();

        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {

            $user = $this->checkUser($model);
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (isset($user) && is_array($user) && isset($user[0]['keyid']) && $user[0]['keyid'] != 0) {

                $lastName = $this->mb_ucfirst(mb_strtolower($model->lastname));
                $firstName = $this->mb_ucfirst(mb_strtolower($model->firstname));
                $secondName = $this->mb_ucfirst(mb_strtolower($model->secondname));

                $patientId = $user[0]['keyid'];
                $patientPhone = $model->phone;
                $patientDob = $model->dob;

                $patientFullName = $lastName . ' ' . $firstName . ' ' . $secondName;
                $patientShortName = $lastName . ' ' . mb_substr($firstName, 0, 1) . '. ' . mb_substr($secondName, 0, 1) . '.';

                $session->set('patient', [
                    'id' => $patientId,
                    'fullName' => $patientFullName,
                    'shortName' => $patientShortName,
                    'dob' => $patientDob,
                    'phone' => $patientPhone
                ]);

                return ['success' => true, 'user' => $user[0]];

            } else {
                return ['success' => false, 'error' => $user[0]];
            }
        }
        return $this->render('auth', compact('model'));
    }

    /**
     * Авторизация пациента по сканеру
     * @return string
     * @throws \Exception
     */
    public function actionAuthScanner()
    {
        $session = Yii::$app->session;
        $session->remove('patient');

        $session->set('isHome', '1');
        $spec = $session->get('speciality');
        $doc = $session->get('doctor');
        $rnumb = $session->get('rnumb');

        return $this->render('auth-scanner', compact('spec', 'doc', 'rnumb'));
    }

    /**
     * Выход из системы авторизованного пользователя
     *
     * @param $rnumbid
     *
     * @return string
     */
    public function actionPrint($rnumbId)
    {
        $session = yii::$app->session;
        $this->layout = 'print';
        $patientShortName = $session->get('patient')['shortName'];


        $generator = new BarcodeGeneratorSVG();
        $rnumb = $this->getRnumbInfo($rnumbId)[0];

        return $this->render('print', compact('rnumb', 'generator', 'patientShortName'));
    }

    /**
     * Выход из системы авторизованного пользователя
     *
     * @param $rnumbid
     *
     * @return array
     */
    public function actionRemoveRnumb()
    {
        If (Yii::$app->getRequest()->isPost) {
            $rnumbId = Yii::$app->request->getBodyParam('rnumbId');
            $rnumbRemove = $this->deleteRnumb($rnumbId);
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (isset($rnumbRemove) && is_array($rnumbRemove) && isset($rnumbRemove[0]['err_code']) && $rnumbRemove[0]['err_code'] == 0) {
                return ['success' => true];
            } else {
                return ['success' => false];
            }
        }
    }

	public function actionQueue($queueId)
    {
		$session = Yii::$app->session;
		$session->remove('isHome');
		$session->remove('QUEUE_TALON');
		$patient = $session->get('patient');
		
		$talon  = $this->getNewTalon($queueId, $patient['id']);
		$talon = $talon[0];
		
		if (isset($talon) && isset($talon['TALON_ID']) && $talon['TALON_ID'] != -1) {
			$session->set('QUEUE_TALON', $talon);
			return $this->render('success-queue', compact('talon'));
		} else {
			return $this->render('success-queue', compact('talon'));
		}
    }
	
	public function actionQueuePrint()
    {
		$session = Yii::$app->session;
		$this->layout = 'print';
        $talon  = $session->get('QUEUE_TALON');
		$generator = new BarcodeGeneratorSVG();

        return $this->render('print-queue', compact('talon', 'generator'));
    }

    /**
     * Выход из системы авторизованного пользователя
     */
    public function actionExit()
    {
        $session = Yii::$app->session;
        if (Yii::$app->request->isAjax) {
            $session->remove('patient');

            return json_encode(['success' => true]);
        } else {
            $session->remove('patient');

            return $this->redirect(['site/index']);
        }
    }
}
