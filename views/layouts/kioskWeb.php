<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\QueuewebAsset;
use yii\helpers\Html;
QueuewebAsset::register($this);
$homePage = Yii::$app->params['homePage'];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--Следующий тэг требует Bootstrap4-->
    <?php $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);?>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!--<div class="ajaxwait"></div>-->
<!--<div class="ajaxwait_image">-->
<!--    --><?//= Html::img('@web/images/autoLoader/ajaxloader.gif') ?>
<!--</div>-->

<?= $this->render('headerWeb') ?>

<main class="content">
    <?= $content ?>
</main>

<?= $this->render('footerWeb') ?>

<?php $this->endBody() ?>

<script>
    $(document).ready(function () {
        // $(".ajaxwait, .ajaxwait_image").hide();
        // $(".ajaxwait, .ajaxwait_image").ajaxSend(function (event, xhr, options) {
        //     $(this).show();
        // }).ajaxStop(function () {
        //     $(this).hide();
        // });

        $('#home').on('click', function () {
            window.location.replace('<?php echo $homePage; ?>');
        });

        $('#back').on('click', function () {
            window.history.back();
        });
    });
</script>

</body>
</html>
<?php $this->endPage() ?>
<?php //var_dump($_SESSION);?>