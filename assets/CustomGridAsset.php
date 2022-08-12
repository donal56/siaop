<?php
    namespace app\assets;

    use yii\web\AssetBundle;

    class CustomGridAsset extends AssetBundle {

        public $basePath = '@webroot';
        public $baseUrl = '@web';

        public $css = [];

        public $js = [
            "js/custom-grid.js"
        ];

        public $depends = [
            "yii\grid\GridViewAsset",
        ];
    }