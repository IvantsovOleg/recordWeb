<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="site-index">

    <!-- div class="row" -->
    <!-- div class="col-md-7 text-center hidden-sm hidden-xs" -->
    <!-- ?= Html::img('@web/images/logo_main.jpg', ['class' => '']) ? -->
    <!-- /div -->
    <div class="col-sm-10 col-sm-offset-1">
		<?php if ( isset( $btns ) && count( $btns ) > 0 ): ?>
            <div class="row">
				<?php foreach ( $btns as $item ): ?>
                    <div class="col-sm-12">
						<?= Html::a( $item['title'], Url::to( [
							$item['url'],
							'structCode' => $item['struct_code']
						] ), [ 'class' => 'btn btn-block btn-primary btn-kiosk' ] ) ?>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php endif; ?>
    </div>
    <!-- /div -->

</div>
