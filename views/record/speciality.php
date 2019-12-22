<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 27.11.2018
 * Time: 12:39
 */

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Самостоятельная запись';
?>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Самозапись к специалисту</h1>
        <p class="lead">На этой странице Вы можете самостоятельно записаться на прием к врачу,
            без необходимости посещать больницу или совершать телефонные звонки для записи</p>
        <hr class="my-2">
        <?php if (isset ($patient['id'])):?>
            <p class="lead">Вы авторизованы как: <b><?= $patient['fullName']?></b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['site/index']);?>
        <?php endif;?>
        <hr class="my-2">
        <p class="lead">Пожалуйста, выберете специальность</p>
        <hr class="my-2">
        <?php if (isset($speciality) && count($speciality) > 0): ?>
        <div class="row justify-content-center">
            <div class="col-8">
                <?php foreach ($speciality as $item): ?>
                    <?= Html::a($item['spec_name'], Url::to(['record/doctor', 'specid' => $item['spec_id'], 'specname' => $item['spec_name'],]), [
                            'class' => 'btn btn-block btn-success mt-md-4 mb-md-4'
                    ]) ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php
$js = <<<JS
$(document).ready(function () {
    $(".row a").on('click', function() {
        $(".ajaxwait, .ajaxwait_image").show();
    });
});
JS;
$this->registerJs($js);
