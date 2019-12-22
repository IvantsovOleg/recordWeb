<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 29.05.2019
 * Time: 13:45
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>

    <div class="print-container step">
        <h3>
            Результат печати
        </h3>
		
		<?php if (isset($talon) && $talon['TALON_ID'] != -1):?>
			<div class="description">Ожидайте у окна регистратуры</div>
			<?= Html::a('Вернуться на главную страницу', Url::to(['site/index']), ['class' => 'btn btn-back']) ?>
		<?php else:?>
			<div class="description"><?= $talon['MESSAGE']?></div>
			<?= Html::a('Вернуться назад', Url::to(['site/main']), ['class' => 'btn btn-back']) ?>
		<?php endif;?>

    </div>


    <button style="display: none;" class="btnPrint">Печать талона</button>

<?php
$printUrl = Url::to(['site/queue-print']);
$homeUrl = Url::to(['site/index']);

$js = <<<JS
$(document).ready(function () {
    $('.btnPrint').printPage({
        url: '$printUrl',
        attr: "href",
        showMessage: false,
        message:"Печать талона ...",
        afterCallback: function() {
          setTimeout(function() {
            window.location.href = '$homeUrl';
          }, 3000); 
        }
    });
    $(".btnPrint").click();
    
});
JS;
$this->registerJs($js);