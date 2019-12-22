<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 03.06.2019
 * Time: 16:30
 */

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Самостоятельная запись';
?>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Самозапись к специалисту</h1>
        <p class="lead">На этой странице Вы можете посмотреть все Ваши активные записи и при необходимости их отменить</p>
        <hr class="my-2">
        <?php if (isset ($patient['id'])):?>
            <p class="lead">Вы авторизованы как: <b><?= $patient['fullName']?></b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['site/index']);?>
        <?php endif;?>
        <hr class="my-2">
        <div class="cancel-container">
            <?php if (isset($rnumbs) && count($rnumbs) > 0): ?>
                <p class="lead">Ваши предстоящие визиты</p>
                <hr class="my-2">
                <div class="row text-center align-items-center bg-info text-white">
                    <div class="col-2">Дата и время</div>
                    <div class="col-6">Доктор</div>
                    <div class="col-2">Сохранить талон</div>
                    <div class="col-2">Отменить</div>
                </div>
                <?php foreach ($rnumbs as $rnumb): ?>
                    <div class="row text-center align-items-center patient-rnumbs">
                        <div class="col-2"><?= date_create($rnumb['dat'])->Format('d.m.Y H:i') ?></div>
                        <div class="col-6"><?= $rnumb['doc'] ?></div>
                        <div class="col-2"><?= Html::button('Сохранить', [
                                                    'class' => 'btn btn-success btn-rnumb-print btn-block',
                                                    'data-rnumb-id' => $rnumb['numbid']
                                                    ]) ?>
                                           <?= Html::button('', [
                                                    'id' => $rnumb['numbid'], 'hidden' => 'hidden', ]) ?></div>
                        <div class="col-2"><?= Html::button('Отменить', [
                                                    'class' => 'btn btn-danger btn-rnumb-cancel btn-block',
                                                    'data-rnumb-id' => $rnumb['numbid']
                                                    ]) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="lead">Предстоящих визитов не найдено</p>
            <?php endif; ?>
        </div>
        <div>
            <hr class="my-3">
            <div class="row">
                <div class="col-3">
                    <button id="back-to-main" class="btn btn-success btn-block">Вернуться назад</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$footer = '<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>';

yii\bootstrap\Modal::begin([
    'header' => 'Ошибка отмены визита',
    'footer' => $footer,
    'id' => 'modal-user',
    'size' => 'modal-lg',
]);
?>
    <div id='modal-content'>
        <h3 class="text-center">
            Загрузка...
        </h3>
    </div>


<?php yii\bootstrap\Modal::end(); ?>

<?php
$cancelUrl = Url::to(['cancel/delete']);
$printUrl = Url::to(['site/print', 'rnumbId' => '']);
$UrlBackToMain = Url::to(['site/main']);
$js = <<<JS
$(document).ready(function () {
    $('.btn-rnumb-cancel').on('click', function() {
        let tr = $(this).parent().parent();
        let numbid = $(this).attr('data-rnumb-id');
        $.ajax({
            url: '$cancelUrl',
            data: {numbid: numbid},
            type: 'POST',
        }).done(function(data) {
            if (data.success) {
                 tr.fadeOut(600, function(){
                     tr.remove();
                     if ($('.patient-rnumbs').length == 0) {
                         $('.cancel-container').html('<p class="lead">Предстоящих визитов не найдено</p>');
                     }
                 });
            } else {
                $('#modal-user').modal('show')
                    .find('#modal-content')
                    .html(data.error ? data.error : 'Не удалось отменить Ваш визит пожалуйста обратитесь в регистратуру');
            }
        }).fail(function(data) {
            $('#modal-user').modal('show')
                    .find('#modal-content')
                    .html(data.error ? data.error : 'Не удалось отменить Ваш визит пожалуйста обратитесь в регистратуру');
        });
    });
    
   
    $('.btn-rnumb-print').on('click', function() {
        $('#' + $(this).attr('data-rnumb-id')).printPage({
            url: '$printUrl' + $(this).attr('data-rnumb-id'),
            attr: 'href',
            showMessage: false,
            message:"Формирование талона ...",
        });
        $('#' + $(this).attr('data-rnumb-id')).click();
    });
    
    $('#back-to-main').on('click', function(){
        window.location.href = '$UrlBackToMain';
    });
});

JS;
$this->registerJs($js);


