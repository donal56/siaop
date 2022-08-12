<?php

    namespace app\assets;

    use yii\web\AssetBundle;

    class GoogleChartsAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl = '@web';

        public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
        public $css = [];
        public $js = [
            "js/google-charts/loader.js"
        ];

        public $depends = [];
    }
?>