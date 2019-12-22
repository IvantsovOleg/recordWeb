<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 27.11.2018
 * Time: 13:02
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Самостоятельная запись';
?>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Самозапись к специалисту</h1>
        <p class="lead">На этой странице Вы можете самостоятельно записаться на прием к врачу,
            без необходимости посещать больницу или совершать телефонные звонки для записи
        </p>
        <hr class="my-2">
        <?php if (isset ($patient['id'])):?>
            <p class="lead"><b>Уважаемый(ая) <?= $patient['fullName']?>!</b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['site/index']);?>
        <?php endif;?>
        <hr class="my-2">
        <p class="lead" id="action-message">Пожалуйста, подтвердите запись на прием</p>
        <hr class="my-2">
        <?php if (isset ($speciality['specId'])):?>
            <p class="lead">Выбранная специальность: <b><?= $speciality['specName']?></b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['record/speciality']);?>
        <?php endif;?>
        <?php if (isset ($doctor['id'])):?>
            <p class="lead">Выбранный специалист: <b><?= $doctor['docName']?></b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['record/doctor', 'specid' => $speciality['specId'], 'specname' => $speciality['specName']]);?>
        <?php endif;?>
        <?php if (isset ($rnumb['id'])):?>
            <p class="lead">Дата и время преима: <b><?= $rnumb['info']?></b></p>
        <?php else:?>
            <?php Yii::$app->response->redirect(['record/rnumb', 'docid' => $doctor['id'], 'docName' => $doctor['docName']]);?>
        <?php endif;?>
        <hr class="my-4">
        <div class="row">
            <div class="col-3">
                <?= Html::a('Отменить запись', '#',
                    ['id' => 'confirm-cancel', 'rnumbId' => $rnumb['id'], 'class' => 'btn btn-danger btn-block']) ?>
            </div>
            <div class="col-3">
                <?= Html::a('Подтвердить запись', '#',
                    ['id' => 'confirm-record', 'class' => 'btn btn-success btn-block']) ?>
            </div>
            <div class="col-3 btn-tooltip" title="Чтобы сохранить талон необходимо подтвердить текущую запись">
                <?= Html::a('Сохранить талон', '#',
                    ['id' => 'confirm-print', 'class' => 'btn btn-outline-success btn-block disabled']) ?>
            </div>
            <div class="col-3 btn-tooltip" title="Чтобы посмотреть все свои активные талоны необходимо либо подтвердить, либо отменить текущую запись">
                <?= Html::a('Мои талоны', Url::to(['cancel/index']),
                    ['id' => 'patient-records', 'class' => 'btn btn-outline-info btn-block disabled']) ?>
            </div>
        </div>
    </div>
</div>

    <div id="modal-message" class="modal" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ошибка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">Ошибка выполнения команды</div>
                <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Закрыть</button>
<!--                    <button id="go-home" type="button" class="btn btn-success">На главную</button>-->
<!--                    <button id="repeat" type="button" data-dismiss="modal" class="btn btn-success">Повторить ввод</button>-->
                </div>
            </div>
        </div>
    </div>


<?php
$rnumbId = $rnumb['id'];
$printUrl = Url::to(['site/print', 'rnumbId' => $rnumb['id']]);
$homeUrl = Url::to(['site/index']);
$unsetRnumbBlock = Url::to(['site/remove-rnumb']);
$homePage = Yii::$app->params['homePage'];
//$cancelPage = Url::to(['cancel/index']);

$js = <<<JS
$(document).ready(function () {
    //Активация всплывающих подсказок
    $('.btn-tooltip').tooltip();
    
    let modalMessage = $('#modal-message');
    
    //Если нажата кнопка "Подтвердить запись"
    $('#confirm-record').on('click', function (e) {
        $(".ajaxwait, .ajaxwait_image").show();
        e.preventDefault();
        modalMessage.find('.modal-title').html('Подтверждение записи');
        $.ajax({
            type: 'POST',
            url: window.location.href,
            }
        ).done(function (data) {
			$(".ajaxwait, .ajaxwait_image").hide();
			if (data.success) {
			    modalMessage.find('.modal-body').html('Запись успешно подтверждена');
			    modalMessage.find('.modal-footer > button').addClass('btn-success');
			    $('#confirm-cancel').addClass('disabled').attr('aria-disabled', 'true');
			    $('#confirm-record').addClass('disabled').attr('aria-disabled', 'true');
			    $('.btn-tooltip').tooltip('disable');
			    $('#confirm-print').removeClass('btn-outline-success disabled').addClass('btn-success');
			    $('#patient-records').removeClass('btn-outline-info disabled').addClass('btn-info');
			    $('#action-message').html('Запись на прием успешно подтверждена');
			    modalMessage.removeAttr('data-backdrop').modal('show');
			} else {
			    modalMessage.find('.modal-body').html('Подтверждение записи не произошло. ' +
			     'Возникла ошибка запроса к базе данных.' + '<br>' + '<br>' + 'Повторите попытку подтверждения записи.' + 
			     '<br>' + '<br>' + 'Также, Вы можете записаться на прием по телефону' + '<br>' + '+7 (82137) 2-82-22');
			    modalMessage.find('.modal-footer > button').addClass('btn-success');
			    modalMessage.removeAttr('data-backdrop').modal('show');
			}
        }).fail(function () {
			$(".ajaxwait, .ajaxwait_image").hide();
			modalMessage.find('.modal-body').html('Подтверждение записи не произошло. ' +
			     'Возникла ошибка запроса к базе данных.' + '<br>' + '<br>' + 'Повторите попытку записи через 5 минут.' + 
			     '<br>' + '<br>' + 'Также, Вы можете записаться на прием по телефону' + '<br>' + '+7 (82137) 2-82-22');
			modalMessage.find('.modal-footer').html('');
			$('#confirm-cancel').addClass('disabled').attr('aria-disabled', 'true');
			$('#confirm-record').addClass('disabled').attr('aria-disabled', 'true');
			modalMessage.modal('show');
			setTimeout(function() {
			    localStorage.clear();
			    sessionStorage.clear();
			    window.location.href = '$homePage';  
			}, 5000);
        });
    });
    
    //Если нажата кнопка "Отменить запись"
    $('#confirm-cancel').on('click', function (e) {
        $(".ajaxwait, .ajaxwait_image").show();
        modalMessage.find('.modal-title').html('Отмена записи');
        $.ajax({
             type: 'POST',
             data: {rnumbId: $(this).attr('rnumbId')},
             url: '$unsetRnumbBlock',
            }
        ).done(function (data) {
			$(".ajaxwait, .ajaxwait_image").hide();
			if (data.success) {
			    modalMessage.find('.modal-body').html('Запись успешно отменена');
			    modalMessage.find('.modal-footer > button').addClass('btn-success');
			    $('#confirm-cancel').addClass('disabled').attr('aria-disabled', 'true');
			    $('#confirm-record').addClass('disabled').attr('aria-disabled', 'true');
			    $('.btn-tooltip').tooltip('disable');
			    $('#patient-records').removeClass('btn-outline-info disabled').addClass('btn-info');
			    $('#action-message').html('Запись на прием успешно отменен');
			    modalMessage.removeAttr('data-backdrop').modal('show');
			} else {
			    modalMessage.find('.modal-body').html('Отмена записи не произошла. ' +
			     'Возникла ошибка запроса к базе данных. Повторите попытку отмены записи.');
			    modalMessage.find('.modal-footer > button').addClass('btn-success');
			    modalMessage.removeAttr('data-backdrop').modal('show');
			}
        }).fail(function(){
			$(".ajaxwait, .ajaxwait_image").hide();
			modalMessage.find('.modal-body').html('Отмена записи не произошла. ' +
			     'Возникла ошибка запроса к базе данных.' + '<br>' + '<br>' + 'Вы будете переадресованы на главную страницу');
			modalMessage.find('.modal-footer').html('');
			$('#confirm-cancel').addClass('disabled').attr('aria-disabled', 'true');
			$('#confirm-record').addClass('disabled').attr('aria-disabled', 'true');
			modalMessage.modal('show');
			setTimeout(function() {
			    localStorage.clear();
			    sessionStorage.clear();
			    window.location.href = '$homePage';  
			}, 5000);
		});
        });
    
    //Если нажата кнопка "Сохранить талон"
        $('#confirm-print').printPage({
        url: '$printUrl',
        attr: "href",
        showMessage: false,
        message:"Печать талона ...",
//        afterCallback: function() {
//            setTimeout(function() {
//                window.location.href = '$homeUrl';
//                }, 5000); 
//            }
        });
    
//    $('#patient-records').on('click', function (e) {
//        window.location.href = '$cancelPage';
//    })
});
JS;
$this->registerJs($js);
