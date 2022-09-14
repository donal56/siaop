<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

class GoogleMapsAsset extends AssetBundle {
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => View::POS_HEAD];
    public $css = ["js/gmap/gmap.css"];
    public $depends = ["app\assets\AppAsset", "yii\web\JqueryAsset"];
    
    public function init() {
        parent::init();

        // Api key de desarrollo@brain-mexico
        $apiKey = self::getApiKey();

        $this->js = [
            "https://maps.googleapis.com/maps/api/js?key=$apiKey&libraries=places&v=3.exp",
            "js/gmap/gmap.js",
            "js/gmap/custom-overlay.js",
            "js/gmap/timeline.js",
            "js/gmap/dependencies/sliding-marker.min.js",
            "js/gmap/dependencies/marker-animate.js",
            "js/gmap/dependencies/marker-clusterer.min.js"
        ];
    }

    public static function getApiKey() {
        return Yii::$app->params["googleMapsApiKey"];
    }

    public static function getServerSideApiKey() {
        return Yii::$app->params["googleMapsApiKeyServerSide"];
    }
}