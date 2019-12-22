<?php

namespace app\controllers;

use app\models\Auth;
use app\models\AuthScanner;
use Yii;
use yii\web\Response;
use app\models\Scanner;
use yii\httpclient\Client;

class ReaderController extends AppController
{

    public $enableCsrfValidation = false;

    /**
     * Запуск считывания полиса
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionStart()
    {
        $auth = new Scanner();

        $headers = $auth->getHeaders();
        $clientIp = Yii::$app->request->getUserIP();
        $readerUrl = '';

        
        $readerUrl = 'http://' . $clientIp . ':8080/policy-reader/api';
        

        $client = new Client(['baseUrl' => $readerUrl]);

        $request = $client->createRequest()
            ->setUrl('policy/read/start')
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setData(null)
            ->setHeaders($headers);

        $response = $request->send();

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($response->isOk) {
            return $response->data;
        } else {
            return null;
        }
    }

    /**
     * Статус считывания полиса
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionStatus()
    {
        $auth = new Scanner();

        $headers = $auth->getHeaders();
        $clientIp = Yii::$app->request->getUserIP();
        $readerUrl = '';

        
        $readerUrl = 'http://' . $clientIp . ':8080/policy-reader/api';
        

        $client = new Client(['baseUrl' => $readerUrl]);

        $request = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('policy/read/status')
            ->setHeaders($headers);

        $response = $request->send();

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($response->isOk) {
            return $response->data;
        } else {
            return null;
        }
    }

    /**
     * Информация о сервисе
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionInfo()
    {
        $auth = new Scanner();
        $clientIp = Yii::$app->request->getUserIP();
        $readerUrl = '';

        
        $readerUrl = 'http://' . $clientIp . ':8080/policy-reader/api';
        
		//print_r($readerUrl); die();

        $client = new Client(['baseUrl' => $readerUrl]);

        $request = $client->createRequest()
            ->setUrl('info');

		//print_r($request); die();
			
        $response = $request->send();

		
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($response->isOk) {
            return $response->data;
        } else {
            return null;
        }
    }

    /**
     * Статус считывания полиса
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionGetData()
    {
        $auth = new Scanner();

        $headers = $auth->getHeaders();
        $clientIp = Yii::$app->request->getUserIP();
        $readerUrl = '';

       
        $readerUrl = 'http://' . $clientIp . ':8080/policy-reader/api';
        

        $client = new Client(['baseUrl' => $readerUrl]);

        $request = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('policy')
            ->setHeaders($headers);

        $response = $request->send();

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($response->isOk) {
            return $response->data;
        } else {
            return null;
        }
    }

    /**
     * Остановка считывания полиса
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionStop()
    {
        $auth = new Scanner();

        $headers = $auth->getHeaders();
        $clientIp = Yii::$app->request->getUserIP();
        $readerUrl = '';

        if (isset($clientIp)) {
            $readerUrl = 'http://' . $clientIp . ':8080/policy-reader/api';
        }

        $client = new Client(['baseUrl' => $readerUrl]);

        $request = $client->createRequest()
            ->setUrl('policy/read/stop')
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('POST')
            ->setData(null)
            ->setHeaders($headers);

        $response = $request->send();

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($response->isOk) {
            return $response->data;
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    public function actionSetPolicy()
    {
        $session = Yii::$app->session;
        $session->remove('patientByScanner');

        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if ($request->isPost) {
            $patient = json_decode($request->getRawBody());

	        $patientByScanner = [
		        'omsNumber' => $patient->omsNumber,
		        'lastName' => $patient->lastName,
		        'firstName' => $patient->firstName,
		        'secondName' => $patient->secondName,
		        'sex' => $patient->sex,
		        'birthDate' => $patient->birthDate,
		        'beginDate' => $patient->beginDate,
		        'expireDate' => $patient->expireDate,
		        'ogrn' => $patient->ogrn,
		        'okato' => $patient->okato,
		        'typeReader' => $patient->typeReader,
	        ];

	        $session->set('patientByScanner', $patientByScanner);


            $model = new AuthScanner();
            $model->lastname = $patient->lastName;
            $model->firstname = $patient->firstName;
            $model->secondname = $patient->secondName;
            $model->dob = $patient->birthDate;
            $model->police = $patient->omsNumber;

            $user = $this->checkUserForScanner($model);
            if (isset($user) && is_array($user) && isset($user[0]['keyid']) && $user[0]['keyid'] != 0) {

                $patientId = $user[0]['keyid'];
                $lastName = $this->mb_ucfirst(mb_strtolower($user[0]['lastname']));
                $firstName = $this->mb_ucfirst(mb_strtolower($user[0]['firstname']));
                $secondName = $this->mb_ucfirst(mb_strtolower($user[0]['secondname']));
                $patientFullName = $lastName . ' ' . $firstName . ' ' . $secondName;
                $patientShortName = $lastName . ' ' . mb_substr($firstName, 0, 1) . '. ' . mb_substr($secondName, 0, 1) . '.';

                $session->set('patient', ['id' => $patientId, 'fullName' => $patientFullName, 'shortName' => $patientShortName]);
                return ['success' => true, 'user' => $user[0]['keyid']];
            } else {
                return ['success' => false, 'user_error' => $user[0]['err_text']];
            }
        } else {
            return ['success' => false, 'user_error' => 'Допустимы только POST запросы'];
        }
    }
}
