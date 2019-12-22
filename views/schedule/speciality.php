<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 27.11.2018
 * Time: 12:39
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => 'Выбор специальности'];
?>

<div class="speciality step">

    <div class="title">
        Пожалуйста, выберите специальность
    </div>

    <div class="container-fluid">
        <?php if (isset($speciality) && count($speciality) > 0): ?>
        <div class="row">
            <?php if (count($speciality) > 5): ?>
            <div class="col-sm-10 col-sm-offset-1">
                <?php else: ?>
                <div class="col-sm-10 col-sm-offset-1">
                    <?php endif; ?>
                    <div class="swiper-container swiper-box" style="height: 600px;">
                        <div class="swiper-wrapper">
                            <?php foreach ($speciality as $item): ?>
                                <div class="swiper-slide">
                                    <?= Html::a($item['spec_name'], Url::to([
                                        'schedule/doctor',
                                        'specid' => $item['spec_id'],
                                        'specname' => $item['spec_name'],
                                        'structCode' => $structCode
                                    ]), [
                                        'class' => 'btn btn-block btn-primary btn-kiosk-sm'
                                    ]) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php if (count($speciality) > 5): ?>
                    <div class="col-sm-1 pagination-container">
                        <button class="btn btn-default btn-pagination button-prev">
                            <i class="fas fa-chevron-circle-up"></i>
                        </button>
                        <button class="btn btn-default btn-pagination button-next">
                            <i class="fas fa-chevron-circle-down"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
