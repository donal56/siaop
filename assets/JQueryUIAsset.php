<?php

    namespace app\assets;

    use yii\web\AssetBundle;

    class JQueryUIAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl = '@web';

        public $css = [];
        public $js = [
            "librerias/jquery-ui/jquery-ui.js"
        ];

        public $depends = [
            "yii\web\JqueryAsset"
        ];
    }
?>