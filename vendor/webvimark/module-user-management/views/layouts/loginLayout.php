<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$this->title = 'Autorización';

$this->registerCssFile("@web/css/style.css");

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" translate= "no" class="h-100">
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <link rel="shortcut icon" href="/img/logo.png" />
        <?php $this->head() ?>
    </head>
    <body class="vh-100">
        <?php $this->beginBody() ?>
            <?= $content ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>