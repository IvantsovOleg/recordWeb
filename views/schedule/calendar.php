<div class="info auth-info">
    <p style="font-weight: 700;">Рассписание врача:</p>
    <?php if (isset($specname)): ?>
        <div>Выбранная специальность: <strong><?= $specname ?></strong></div>
    <?php endif; ?>
    <?php if (isset($docname)): ?>
        <div>Врач: <strong><?= $docname ?></strong></div>
    <?php endif; ?>
</div>


<?= yii2fullcalendar\yii2fullcalendar::widget([
    'options' => [
        'lang' => 'ru',

        //... more options to be defined here!
    ],
    'clientOptions' => [
        'height' => 700
    ],
    'events' => $events
//    'events' => Url::to(['/timetrack/default/jsoncalendar'])
]);
?>
