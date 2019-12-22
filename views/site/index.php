<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\ActiveField;
$this->title = 'Самостоятельная запись';
?>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Самозапись к специалисту</h1>
        <p class="lead">На этой странице Вы можете самостоятельно записаться на прием к врачу,
            без необходимости посещать больницу или совершать телефонные звонки для записи</p>
        <hr class="my-2">
        <p class="lead">Для того, чтобы самостоятельно записаться к врачу воспользуйтесь формой ниже</p>
        <hr class="my-2">
        <p class="lead">Пожалуйста, авторизуйтесь</p>
        <hr class="my-2">
        <?php $form = ActiveForm::begin( [
            'id' => 'user-auth-form',
            'method' => 'post',
            'action' => ['site/index'],
            'fieldConfig' => [
                'template' => "<div class=\"row\">\n{label}\n<div class=\"col-sm-5\">{input}</div></div>",
                'labelOptions' => [ 'class' => 'col-sm-3 col-form-label' ],
            ],])?>

        <?= $form->field( $model, 'lastname' )->label('Фамилия:*')->textInput( ['placeholder' => 'Обязательное поле']) ?>
        <?= $form->field( $model, 'firstname' )->label( 'Имя:*' )->textInput( ['placeholder' => 'Обязательное поле']) ?>
        <?= $form->field( $model, 'secondname' )->label( 'Отчество:*' )->textInput( ['placeholder' => 'Обязательное поле']) ?>
        <?= $form->field( $model, 'dob' )->label( 'Дата рождения:*' )->textInput( ['id' => 'dob', 'placeholder' => 'Обязательное поле'] ) ?>
        <?= $form->field( $model, 'phone' )->label( 'Номер телефона:' )->textInput( ['id' => 'phone']) ?>
        <hr class="my-4">
        <div class="col-3" style="padding-left: 0">
            <?= Html::submitButton('Далее', ['class' => 'btn btn-success btn-block']) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>

    <div id="modal-message" class="modal" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Сообщение об ошибке</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button id="go-home" type="button" class="btn btn-success">На главную</button>
                    <button id="repeat" type="button" data-dismiss="modal" class="btn btn-success">Повторить ввод</button>
                </div>
            </div>
        </div>
    </div>

<?php
$successUrl = Url::to(['site/main']);
$homePage = Yii::$app->params['homePage'];
$js = <<<JS
$(document).ready(function () {
    $("#dob").mask("99.99.9999");
    $("#phone").mask("+7 (999) 999-99-99");
    $("#go-home").on('click', function() {
        window.location.replace('$homePage');
    });
    
    $("#user-auth-form").on('beforeSubmit', function () {
        var yiiform = $(this);
        $(".ajaxwait, .ajaxwait_image").show();
        $.ajax({
            type: yiiform.attr('method'),
            url: yiiform.attr('action'),
            data: yiiform.serializeArray(),
            }
        ).done(function (data) {
            $(".ajaxwait, .ajaxwait_image").hide();
            if (data.success) {
                window.location.href = '$successUrl';
            } else {
                $('#modal-message').modal('show')
                    .find('.modal-body')
                    .html('Указанный пациент в базе не найден. Проверьте введенные данные. Также, Вы можете записаться по телефону<br>+7 (81837) 2-82-22');
            }
        }).fail(function (error) {
            $(".ajaxwait, .ajaxwait_image").hide();
            $('#modal-message').modal('show')
                .find('.modal-body')
                .html('Не удалось установить соединение с базой данных. Попробуйте повторить попытку через 5 минут.');
        });
        
        return false;
    });
    
});
JS;
$this->registerJs($js);
