<?php

namespace app\controllers;

use Yii;
use yii\web\Response;

class CancelController extends AppController
{

    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $session = Yii::$app->session;
        $patient = $session->get('patient');

        $rnumbs = $this->getRnumbsByPatient($patient['id']);

        return $this->render('index', compact('rnumbs', 'patient'));
    }

    public function actionDelete()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->getRequest();

        $patient = $session->get('patient');

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($request->isAjax) {
            $numbid = $request->post('numbid');

            if (isset($numbid)) {
                $result = $this->deleteRnumb($numbid);
                if (isset($result) && $result[0]) {
                    return ['success' => true, 'error' => ''];
                } else {
                    return ['success' => true, 'error' => ''];
                }
            } else {
                return ['success' => true, 'error' => ''];
            }
        } else {
            return ['success' => false, 'error' => ''];
        }
    }
}
