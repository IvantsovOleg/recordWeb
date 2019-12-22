<?php

/* @var $this yii\web\View */

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
        <p class="lead">Пожалуйста, выберете тип записи</p>
        <hr class="my-2">
        <?php if (isset($btns) && count($btns) > 0): ?>
            <?php foreach ($btns as $item): ?>
                <div class="row justify-content-center">
                    <div class="col-8">
                        <?= Html::a($item['title'], Url::to([$item['url']]),
                            ['class' => 'btn btn-success btn-block mt-md-2 mb-md-2'])?>
                    </div>
                </div>
            <?php endforeach; ?>
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
