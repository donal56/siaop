<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

class GoogleMapsAsset extends AssetBundle {
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => View::POS_HEAD];
    public $css = ["css/GMap/GMap.css"];
    public $depends = ["app\assets\AppAsset", "yii\web\JqueryAsset"];
    
    public function init() {
        parent::init();

        // Api key de desarrollo@brain-mexico
        $apiKey = self::getApiKey();

        $this->js = [
            "https://maps.googleapis.com/maps/api/js?key=$apiKey&libraries=places&v=3.exp",
            "js/GMap/GMap.js",
            "js/GMap/CustomOverlay.js",
            "js/GMap/Timeline.js",
            "js/GMap/dependencies/SlidingMarker.min.js",
            "js/GMap/dependencies/markerAnimate.js",
            "js/GMap/dependencies/MarkerClusterer.min.js"
        ];
    }

    public static function getApiKey() {
        return Yii::$app->params["googleMapsApiKey"];
    }

    public static function getServerSideApiKey() {
        return Yii::$app->params["googleMapsApiKeyServerSide"];
    }
}