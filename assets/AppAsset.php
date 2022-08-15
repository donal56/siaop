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

class AppAsset extends AssetBundle {
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/layout.css',

		'vendor/owl-carousel/owl.carousel.css',
		'vendor/jquery-nice-select/css/nice-select.css'
    ];
	
    public $js = [
        'js/bootstrap/bootstrap.bundle.min.js',
        'js/popper/popper.min.js',
        'js/perfect-scrollbar/perfect-scrollbar.min.js',
        'js/metismenu/metismenu.min.js',
        'js/init.js',
        'js/utils.js',
		
		'vendor/jquery-nice-select/js/jquery.nice-select.min.js',
		'vendor/peity/jquery.peity.min.js',
		'vendor/owl-carousel/owl.carousel.js'
    ];

    public $depends = [
        'yii\web\YiiAsset'     
    ];
}
