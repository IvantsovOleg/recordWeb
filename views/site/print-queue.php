<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 18.03.2019
 * Time: 14:04
 */

use yii\helpers\Html;
?>


<?php if (isset($talon)): ?>

    <div class="print-talon">

        <div class="print-talon__queue">
            <?php if (isset($talon['QUEUE'])): ?>
                <?= $talon['QUEUE'] ?>
            <?php endif; ?>
        </div>
        <div class="print-talon__description">Ваш номер в очереди</div>

        <div class="print-talon__code">
            <?php if (isset($talon['TALON_CODE'])): ?>
                <?= $talon['TALON_CODE'] ?>
            <?php endif; ?>
        </div>

        <div class="print-talon__barcode">
            <?php if (isset($talon['BARCODE'])): ?>
                <?= $generator->getBarcode($talon['BARCODE'], $generator::TYPE_CODE_128) ?>
            <?php endif; ?>
        </div>

        <div class="print-talon__time">
            <?= date("d.m"); ?>&nbsp;<?= date("G:i"); ?>
        </div>
    </div>

<?php endif; ?>