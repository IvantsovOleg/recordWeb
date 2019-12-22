<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 18.03.2019
 * Time: 14:04
 */

use yii\helpers\Html;
?>

<?php if (isset($rnumb)): ?>

    <div class="print-talon">

        <div>
            <?php if (isset($rnumb['depname'])): ?>
                <div>Поликлиника</div>
                <div class="print-talon__doctor"><?= $rnumb['depname']?></div>
            <?php endif; ?>
        </div>

        <div>
            <?php if (isset($rnumb['ADDR'])): ?>
                <div>Адрес</div>
                <div class="print-talon__doctor"><?= $rnumb['ADDR']?></div>
            <?php endif; ?>
        </div>

        <div>
            <?php if (isset($rnumb['PHONE'])): ?>
                <div>Телефон</div>
                <div class="print-talon__date"><?= $rnumb['PHONE']?></div>
            <?php endif; ?>
        </div>

        <div>
            <?php if (isset($rnumb['spec'])): ?>
                <div>Специальность</div>
                <div class="print-talon__speciality"><?= $rnumb['spec'] ?></div>
            <?php endif; ?>
        </div>

        <div>
            <?php if (isset($rnumb['lastname'])): ?>
                <div>Доктор</div>
                <div class="print-talon__doctor"><?= $rnumb['lastname'] ?> <?= $rnumb['firstname'] ?> <?= $rnumb['secondname'] ?></div>
            <?php endif; ?>
        </div>

        <div>
            <?php if (isset($rnumb['dat_bgn'])): ?>
                <div>Время приема</div>
                <div class="print-talon__date"><?= date_create($rnumb['dat_bgn'])->Format('d.m.Y H:i') ?></div>
            <?php endif; ?>
        </div>

        <div class="print-talon__cabinet">
            <?php if (isset($rnumb['cab'])): ?>
                Кабинет: <?= $rnumb['cab'] ?>
            <?php endif; ?>
        </div>

        <div>
            <?php if (isset($patientShortName)): ?>
                <div>Пациент</div>
                <div class="print-talon__doctor"><?= $patientShortName ?></div>
            <?php endif; ?>
        </div>

        <div class="print-talon__barcode">
            <?php if (isset($rnumb['rnumb_id'])): ?>
                <?= $generator->getBarcode($rnumb['rnumb_id'], $generator::TYPE_CODE_128) ?>
            <?php endif; ?>
        </div>
    </div>

<?php endif; ?>



