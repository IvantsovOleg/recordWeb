<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 27.11.2018
 * Time: 12:39
 */
use yii\bootstrap\Modal;
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
        <?php if (isset ($speciality['specId'])):?>
            <p class="lead">Выбранная специальность: <b><?= $speciality['specName']?></b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['record/speciality']);?>
        <?php endif;?>
        <hr class="my-2">
        <?php if (isset($doctors) && count($doctors) > 0): ?>
        <p class="lead">Пожалуйста, выберете специалиста</p>
        <hr class="my-2">
        <div class="row justify-content-center">
            <div class="col-8">
                <?php foreach ($doctors as $item): ?>
                    <?= Html::a($item['text'],
                                Url::to(['record/rnumb', 'docid' => $item['keyid'], 'docName' => $item['text']]),
                                ['class' => 'btn btn-block btn-success mt-md-4 mb-md-4']) ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else:?>
        <p class="lead"><b>По Вашему запросу ничего не найдено</b></p>
        <hr class="my-2">
        <div class="row">
            <div class="col-3">
                <button id="back" class="btn btn-success btn-block">Вернуться назад</button>
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