<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 27.11.2018
 * Time: 12:39
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = [ 'label' => 'Специальность', 'url' => Url::to( [ 'schedule/speciality' ] ) ];
$this->params['breadcrumbs'][] = [ 'label' => 'Специалист и время приема' ];
?>

<div class="info">
	<?php if ( isset( $specname ) ): ?>
        <div>Выбранная специальность: <?= $specname ?></div>
	<?php endif; ?>
</div>

<div class="doctor-step step">

    <div class="title">
        Пожалуйста, выберите специалиста
    </div>

    <div class="container-fluid">
		<?php if ( isset( $doctors ) && isset( $specid ) && count( $doctors ) > 0 ): ?>
        <div class="row">

			<?php if ( count( $doctors ) > 5 ): ?>
            <div class="col-sm-10 col-sm-offset-1">
				<?php else: ?>
                <div class="col-sm-10 col-sm-offset-1">
					<?php endif; ?>
                    <div class="swiper-container swiper-box" style="height: 600px;">
                        <div class="swiper-wrapper">
							<?php foreach ( $doctors as $item ): ?>
                                <div class="swiper-slide">
									<?= Html::a( $item['text'],
										Url::to( [
											'schedule/calendar',
											'docid'   => $item['keyid'],
											'specid'  => $specid,
											'docname' => $item['text'],
                                            'specname' => $specname,
										] ), [ 'class' => 'btn btn-block btn-primary btn-kiosk-sm' ] ) ?>
                                </div>
							<?php endforeach; ?>
                        </div>
                    </div>
                </div>
				<?php if ( count( $doctors ) > 5 ): ?>
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
