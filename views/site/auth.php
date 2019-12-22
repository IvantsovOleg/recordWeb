<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

    <div class="auth-container">
        <div class="auth-form">

            <h3 class="title text-center">
                Пожалуйста, авторизуйтесь
            </h3>

            <hr class="primary">

			<?php $form = ActiveForm::begin( [
				'id'          => 'user-auth-form',
				'options'     => [ 'class' => 'form-horizontal' ],
				'fieldConfig' => [
					'template'     => "{label}\n<div class=\"col-sm-9\">{input}</div>",
					'labelOptions' => [ 'class' => 'col-sm-3 control-label' ],
				],
			] ) ?>

			<?= $form->field( $model, 'lastname' )->label( 'Фамилия:*' ) ?>
			<?= $form->field( $model, 'firstname' )->label( 'Имя:*' ) ?>
			<?= $form->field( $model, 'secondname' )->label( 'Отчество:*' ) ?>
			<?= $form->field( $model, 'dob' )->label( 'Дата рождения:*' )->textInput( [ 'id' => 'dob' ] ) ?>
			<?= $form->field( $model, 'phone' )->label( 'Номер телефона:' )->textInput( [ 'id' => 'phone' ] ) ?>

			<?php ActiveForm::end() ?>

        </div>

        <div class="keyboard-container">
			<?= $this->render( '_keyboard' ); ?>
        </div>
    </div>

<?php

$footer = Html::a( 'Отмена записи', Url::to( [ 'site/index' ] ), [ 'class' => 'btn btn-danger' ] );
$footer .= '<button type="button" class="btn btn-default" data-dismiss="modal">Повторить ввод</button>';

yii\bootstrap\Modal::begin( [
	'header' => 'Ошибка авторизации',
	'footer' => $footer,
	'id'     => 'modal-user',
	'size'   => 'modal-lg',
] );
?>
    <div id='modal-content'>
        <h3 class="text-center">
            Загрузка...
        </h3>
    </div>


<?php yii\bootstrap\Modal::end(); ?>

<?php

$successUrl = Url::to( [ 'site/main' ] );

$js = <<<JS
$(document).ready(function () {
    $("#dob").mask("99.99.9999");
    $("#phone").mask("(999) 999-9999");
    
     var form = $("form#user-auth-form");

    form.on('beforeSubmit', function () {
        var yiiform = $(this);
         $(".ajaxwait, .ajaxwait_image").show();
        // отправляем данные на сервер
        $.ajax({
                type: yiiform.attr('method'),
                url: yiiform.attr('action'),
                data: yiiform.serializeArray()
            }
        ).done(function (data) {
             $(".ajaxwait, .ajaxwait_image").hide();
            if (data.success) {
                window.location.href = '$successUrl';
            } else {
                $('#modal-user').modal('show')
                    .find('#modal-content')
                    .html(data.error ? data.error.err_text : 'Пожалуйста повторите ввод.');
            }
        }).fail(function (error) {
             $(".ajaxwait, .ajaxwait_image").hide();
            $('#modal-user').modal('show')
                .find('#modal-content')
                .html('Не удалось найти указанное направление');
        });
        
        return false;
    });
    
});
JS;
$this->registerJs( $js );