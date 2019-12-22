<?php

namespace app\controllers;

use Yii;
use yii\web\Response;

class RecordController extends AppController
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $session->remove('speciality');
        $session->remove('doctor');
        $session->remove('rnumb');
        $session->set('isHome', '1');
        return $this->redirect(['record/speciality']);
    }

    /**
     * Список доступных специальностей для записи и киоска
     * @return string
     */
    public function actionSpeciality()
    {
        $session = Yii::$app->session;
        $patient = $session->get('patient');
        $session->remove('speciality');


        $speciality = $this->getSpecialityList($patient['id'], null);
        $url = 'record/doctor';

        return $this->render('speciality', compact('url', 'speciality', 'patient'));
    }

    /**
     * Список доступных врачей по специальности для записи и киоска
     * @param $specid
     * @param $specname
     * @return string
     */
    public function actionDoctor($specid, $specname)
    {
        $session = Yii::$app->session;
        $patient = $session->get('patient');

        $session->set('speciality', ['specId' => $specid, 'specName' => $specname]);
        $speciality = $session->get('speciality');

        $session->remove('doctor');
        $session->remove('selected_rnumb');

        $doctors = $this->getDoctorList($specid, $patient['id'], null);
        return $this->render('doctor', compact('doctors', 'patient', 'speciality'));

    }

    /**
     * Список талонов только для киоска
     * @param $specid
     * @param $docid
     * @param $docname
     * @return string
     */
    public function actionRnumb($docid, $docName)
    {
        $session = Yii::$app->session;
        $session->remove('rnumb');
        $url = 'record/success';

        $session->set('doctor', ['id' => $docid, 'docName' => $docName]);
        $speciality = $session->get('speciality');
        $patient = $session->get('patient');
        $doctor = $session->get('doctor');


        $numbList = $this->getRnumbs($session['speciality']['specId'], $docid);
//        var_dump($numbList); die;

        $i = 0;
        $days = array();
        $date = $dateBegin = $numbList[0]['dd'];
        foreach ($numbList as $index => $numb) {
            if ($date == $numb['dd']) {
                $days[$date]['dd'] = $numb['dd'];
                $days[$date]['dw'] = $numb['dw'];
                if ($dateBegin == $numb['dd']) {
                    $days[$date]['rnumbs'][$i] = $numb;
                } else {
                    $days[$date]['rnumbs'][$i + 1] = $numb;
                }
                $i++;
            } else {
                $i = 0;
                $date = $numb['dd'];
                $days[$date]['dd'] = $numb['dd'];
                $days[$date]['dw'] = $numb['dw'];
                $days[$date]['rnumbs'][$i] = $numb;
            }
        }
        // Возвращение самой ранней даты
        foreach (array_slice($days, 0,1, true) as $key => $value) {
            $minDate = date('Y-m-d',strtotime($key));
            $firstDate = $key;
            break;
        }
        // Возвращение самой поздней даты
        foreach (array_slice($days, count($days)-1,1, true) as $key => $value) {
            $maxDate = date('Y-m-d',strtotime($key));
            break;
        }
        if (Yii::$app->request->isPost) {
            if (isset(Yii::$app->request->post()['rnumbId'])) {
                $rnumbId = Yii::$app->request->post()['rnumbId'];
                $rnumbBlock = $this->setBlockRnumb($rnumbId);
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($rnumbBlock[0]['err_code'] == 0) {
                    return ['success' => true];
                } else {
                    return ['success' => false];
                }
            }
        }
        return $this->render('day', compact('days','patient', 'speciality', 'doctor', 'url', 'firstDate', 'minDate', 'maxDate'));
    }

    /**
     * Список доступных талонов для переданного врача и специальности только веб запись
     * @param $specid
     * @param $docid
     * @param $docname
     * @return string
     */
//    public function actionAjaxNumbs($specid, $docid, $docname)
//    {
//        $numblist = $this->getRnumbs($specid, $docid);
//        $session = Yii::$app->session;
//        $session->set('doctor', ['docId' => $docid, 'docName' => $docname]);
//
//        $i = 0;
//        $rnumbGroupDate = array();
//        $date = $dateBegin = $numblist[0]['dd'];
//
//        foreach ($numblist as $index => $numb) {
//            if ($date == $numb['dd']) {
//
//                $rnumbGroupDate[$date]['dd'] = $numb['dd'];
//                $rnumbGroupDate[$date]['dw'] = $numb['dw'];
//                if ($dateBegin == $numb['dd']) {
//                    $rnumbGroupDate[$date]['rnumbs'][$i] = $numb;
//                } else {
//                    $rnumbGroupDate[$date]['rnumbs'][$i + 1] = $numb;
//                }
//
//                $i++;
//            } else {
//                $i = 0;
//                $date = $numb['dd'];
//                $rnumbGroupDate[$date]['dd'] = $numb['dd'];
//                $rnumbGroupDate[$date]['dw'] = $numb['dw'];
//                $rnumbGroupDate[$date]['rnumbs'][$i] = $numb;
//            }
//        }
//
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return json_encode($rnumbGroupDate);
//    }

    /**
     * Блокировка талона запись и киоск
     * @return Response
     */
//    public function actionAjaxNumbBlock()
//    {
//        $session = Yii::$app->session;
//        $patient = $session->get('patient');
//
//        if (Yii::$app->request->isAjax) {
//            $rnumb = Yii::$app->request->post();
//
//            if (isset($rnumb)) {
//                $session->set('selected_rnumb', $rnumb);
//            }
//
//            $this->setBlockRnumb($rnumb['id']);
//
//            if (isset($patient)) {
//                return $this->redirect(['record/record-auth']);
//            } else {
//                return $this->redirect(['site/auth']);
//            }
//        } else {
//            return $this->refresh();
//        }
//    }

    /**
     * Запись на прием пациента
     * @return array|string
     */
    public function actionSuccess($rnumbId, $rnumbInfo)
    {
        $session = Yii::$app->session;
        $session->set('rnumb', ['id' => $rnumbId, 'info' => $rnumbInfo]);

        $speciality = $session->get('speciality');
        $doctor = $session->get('doctor');
        $patient = $session->get('patient');
        $rnumb = $session->get('rnumb');

        $request = Yii::$app->getRequest();
        if ($request->isAjax) {
            $success = $this->complete($patient['id'], $rnumb['id']);
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (isset($success) && is_array($success) && isset($success[0]['err_code']) && $success[0]['err_code'] == 0) {
                return ['success' => true];
            } else {
                return ['success' => false];
            }
        }

        return $this->render('success', compact('patient', 'rnumb', 'speciality', 'doctor'));
    }

    public function generateNonce($length = 8)
    {
        try {
            $bytes = random_bytes($length);
            return base64_encode($bytes);
        } catch (\Exception $e) {
        }
    }
}
