<?php

    namespace app\assets;

    use yii\web\AssetBundle;

    class jQueryTransferAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl = '@web';
        public $css = [
            "librerias/jquery-transfer/icon_font.css",
            "librerias/jquery-transfer/jquery-transfer.css",
        ];
        public $js = [
        	"librerias/jquery-transfer/jquery.transfer0.0.2.js"
        ];
        public $depends = 
        [
            "yii\web\JqueryAsset"
        ];
    }
?>