<?php

    namespace app\assets;

    use yii\web\AssetBundle;

    class jQueryTransferAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl = '@web';
        public $css = [
            "css/jQueryTransfer/icon_font.css",
            "css/jQueryTransfer/jquery.transfer.css",
        ];
        public $js = [
        	"js/jQueryTransfer/jquery.transfer.js?v=0.0.2",
        ];
        public $depends = 
        [
            "yii\web\JqueryAsset"
        ];
    }
?>