<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use app\components\Utils\DebugUtils;
use app\components\Utils\StringUtils;
use app\models\Menu;
use webvimark\modules\UserManagement\models\User;
use yii\web\View;

AppAsset::register($this);

$this->registerJs("
    $(window).on('load',function(){
		setTimeout(function(){
			$('.front-view-slider').owlCarousel({
				loop: false,
				margin: 30,
				nav: true,
				autoplaySpeed: 3000,
				navSpeed: 3000,
				autoWidth: true,
				paginationSpeed: 3000,
				slideSpeed: 3000,
				smartSpeed: 3000,
				autoplay: false,
				animateOut: 'fadeOut',
				dots: true,
				navText: ['', ''],
				responsive: {
					0: { items: 1 },
					480: { items: 1 },			
					767: { items: 3 },
					1750: { items: 3 }
				}
			});
		}, 1000); 
	});
", View::POS_END, 'navbar');
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" translate="no">

<head>
    <title><?= Html::encode($this->title) ?></title>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">

    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>

    <link rel="shortcut icon" href="/img/logo.png" />
</head>

<body>
    <?php $this->beginBody() ?>

    <!-- Preloader -->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>

    <div id="main-wrapper">

        <!-- Header -->
        <div class="nav-header">
            <a href="/" class="brand-logo">
                <img src="/img/logo_inv.png" alt="Logo" style="height: 4.5rem">
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                <?= Html::encode($this->title) ?>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">

                            <li class="nav-item">
                                <b style="font-size: 17px; color: white"><?= Html::encode(Yii::$app->user->identity->username) ?></b>
                            </li>

                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <g data-name="Layer 2" transform="translate(-2 -2)">
                                            <path id="Path_20" data-name="Path 20" d="M22.571,15.8V13.066a8.5,8.5,0,0,0-7.714-8.455V2.857a.857.857,0,0,0-1.714,0V4.611a8.5,8.5,0,0,0-7.714,8.455V15.8A4.293,4.293,0,0,0,2,20a2.574,2.574,0,0,0,2.571,2.571H9.8a4.286,4.286,0,0,0,8.4,0h5.23A2.574,2.574,0,0,0,26,20,4.293,4.293,0,0,0,22.571,15.8ZM7.143,13.066a6.789,6.789,0,0,1,6.78-6.78h.154a6.789,6.789,0,0,1,6.78,6.78v2.649H7.143ZM14,24.286a2.567,2.567,0,0,1-2.413-1.714h4.827A2.567,2.567,0,0,1,14,24.286Zm9.429-3.429H4.571A.858.858,0,0,1,3.714,20a2.574,2.574,0,0,1,2.571-2.571H21.714A2.574,2.574,0,0,1,24.286,20a.858.858,0,0,1-.857.857Z" />
                                        </g>
                                    </svg>
                                    <span class="badge light text-white bg-primary rounded-circle">+9</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div id="DZ_W_Notification1" class="widget-media dlab-scroll p-3" style="height:380px;">
                                        <ul class="timeline">
                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media me-2">
                                                        <img alt="image" width="50" src="img/avatar.jpg">
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="mb-1">Notificación</h6>
                                                        <small class="d-block">01 de enero del 2022 - 12:00 PM</small>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <a class="all-notification" href="javascript:void(0);">
                                        Mirar todas las notificaciones <i class="ti-arrow-end"></i>
                                    </a>
                                </div>
                            </li>

                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <img src="img/avatar.jpg" width="20" alt="" />
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="/" class="dropdown-item ai-icon">
                                        <svg id="icon-user2" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <span class="ms-2">Perfil </span>
                                    </a>
                                    <a href="/site/logout" class="dropdown-item ai-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span class="ms-2">Cerrar sesión </span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Navbar -->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <?php 
                        foreach(Menu::getItems() as $item) {
                            $etiqueta = $item['etiqueta'];
                            $icono = $item['icono'];

                            echo "<li>";
                            echo "<a class= 'has-arrow' href= 'javascript:void()' aria-expanded= 'false'>
                                        <i class= '$icono'></i>
                                        <span class= 'nav-text'>$etiqueta</span>
                                    </a>";

                            foreach($item['submenu'] as $subitem) {
                                $etiqueta = $subitem['etiqueta'];
                                $url = $subitem['url'];

                                echo "<ul aria-expanded= 'false'>
                                        <li><a href= '$url'>$etiqueta</a></li>
                                    </ul>";
                            }
                            echo "</li>";
                        }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <div class="content-body">

            <div class="container-fluid">
                <!--- Banner de dev/test -->
                <?php if (DebugUtils::esEntornoDePruebas()) : ?>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body" style="padding: 0.65rem">
                                    <h4 style='text-align: center; color: green'>
                                        SITIO DE DESARROLLO/PRUEBAS(<?= StringUtils::substringAfter(Yii::$app->db->dsn, "dbname=") ?>)
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="copyright">
                <p>CDRG 2022</p>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>