<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class QueuewebAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	    'css/queueWeb.css',
        'css/datepicker/jquery-ui.css',
//        'css/swiper/swiper.css',
        'css/swiper/swiper.min.css',
        'css/all.css',
        '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css',
        '//fonts.googleapis.com/css?family=PT+Sans:700&display=swap',
	];
	public $js = [
        'js/jquery.mask.min.js',
        'js/layout.js',
        'js/jquery.printPage.js',
//        'js/swiper/swiper.js',
        'js/swiper/swiper.min.js',
        'js/datepicker/jquery-ui.js',
        'js/datepicker/datepicker-ru.js',
        '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js',
        '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
//		'yii\bootstrap\BootstrapAsset',
	];
}
