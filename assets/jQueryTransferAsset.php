<?php

    namespace app\assets;

    use yii\web\AssetBundle;

    class jQueryTransferAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl = '@web';
        public $css = [
            "css/jquery-transfer/icon_font.css",
            "css/jquery-transfer/jquery-transfer.css",
        ];
        public $js = [
        	"js/jquery-transfer/jquery.transfer0.0.2.js"
        ];
        public $depends = 
        [
            "yii\web\JqueryAsset"
        ];
    }
?>