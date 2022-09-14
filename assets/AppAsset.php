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
        'librerias/tempus-dominus/tempus-dominus.css'
    ];
	
    public $js = [
        'librerias/bootstrap/bootstrap.bundle.min.js',
        'librerias/bootstrap/popper.min.js',
        'librerias/perfect-scrollbar/perfect-scrollbar.min.js',
        'librerias/metismenu/metismenu.min.js',
        "librerias/tempus-dominus/tempus-dominus.js",
        'js/init.js',
        'js/utils.js',
    ];

    public $depends = [
        'yii\web\YiiAsset'     
    ];
}
