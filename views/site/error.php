<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$this->title = 'Error';

?>
<div class="site-error">
    <div style= "margin-top: 20px">
        <?php if(is_a($exception, 'yii\web\NotFoundHttpException') && Yii::$app->user->isGuest) : ?>
            <?php Yii::$app->response->redirect(Url::to(['/'])); ?>


        <?php elseif(is_a($exception, 'yii\web\ForbiddenHttpException') || is_a($exception, 'yii\web\UnauthorizedHttpException')) : ?>
            <img alt="Ha ocurrido un error" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEAAQMAAABmvDolAAAABlBMVEUAAAD///+l2Z/dAAAAAnRSTlP/AOW3MEoAAAAJcEhZcwAABzsAAAc7AXiCJvkAAANDSURBVGje7ZpBjqswDIYdscgyR8hROBocjaNwBJZZIPISSMEmjl1NX6WRhkhtpfINE/9xbMcUYowb3IeLa/4Y0kVIr6kC/AF0B1DfAPoYoNwiAaEGhvKl34GZA5b90+7AWAOvvzI7UF83520zsLLA9JotcHPszv/rE7DUgD0BlwDGCHdOzCZgkoAuAYyV/lTXJAA4pU/TImzQXoqsKQsMGGB0SvqdtvcQJKXTfIEXEgELC5ziOBCVzgCv9AlYDvDIRyxMnE6XOB0HDMiJWCASgHXZSxzDAAYpzQJYSBaweLdygKMAqxMzc6JTFK8nnTYZiNxupDIEEeg0wEZuu1IZZhHwXNi7yTAqMkRNhk2TYf2yDF6SoQ+7lZMwv93KURB5yvlDUCAuRpAhzT8GK8iQM9Tq2jLkBBU335ahz0DsmzKY+BpT08YyxqaNZbRtPMbWXKMy1rYIx1i0KcxtlSQrrzk2rHSale59K4O0UoKVvWIl0ukwYm4Dr6TfEnIjFUgLYPYn1cnVa0oBXwtKgb5eEgoM9aLSxWQEI4B5C1hawFTcK3wCWMb7iVNzwIAB9xaw/RhYeIA4rf8MCK9NImycbwMrP8mIAUYHqwG9AlxheL9Qr6alQO0PgwKgGLgDlU96BcBT3IH7vnAEWO/ACPEO3PamqwGyu+kMDoDEB38Dwj3CRAZweCmKcQQgUa74Mo3V2IgSNgYSq3sUw8rUKdCFm7PNFzCxURr51MgBAQFswhwvYOOAFQkaOGBCwNIq/nwzrRbDXDP1l+9cM+cVZWwz7Qbs+otYiEa5mBXy9rX9RqWcXj+r2Dvt2AFKMfyMZzzjSwOnlQd4gJz69sI9CM2JB3gAXAEs5e2LwH4E+ghYczGzCD25LZdDs9S0A4gyMBoJyF1iK/UvE7BKnb3XUWL8OQBRKxoHoRv0HtALTbHrwBPkolJukVqhn3QVz5OW+bRG8aZ1adU+76ylXyU/d5UN498oTBQzza8AJmXx/gOgOJDVAeV05XRA9nL+2SIF5DMk9KCcEQcd0J7qgHzQNfyDYBxRQN7NNionWcc/zsYBBeTTdh9BO/CDGP1N67E+znJiWPOtnxagsAlS7C0/TmjfIueXf8I2sjRJl6tdAAAAAElFTkSuQmCC" style= "width: 6%" />


        <?php elseif(is_a($exception, 'yii\db\IntegrityException') && str_contains($exception->errorInfo[2], 'Duplicate entry')) : ?>
            <img alt="Ha ocurrido un error" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEAAQMAAABmvDolAAAABlBMVEUAAAD///+l2Z/dAAAAAnRSTlP/AOW3MEoAAAAJcEhZcwAABzsAAAc7AXiCJvkAAAHaSURBVGje7dpRboQgEAZgjA88cgSPskeTo3EUjsCjD0SKGMW6zPxuNM2mmUn60n51B5VlcFQppVkRMeY/qvzjKNCvgDxAOUQGEw2GAjwNdAGWBl0BiokFRA68Mpg4MGQQOGAy8BzQGTgO9BlYDnQZKDaSmnkwYhB58FITDwYMAg8MBp4HGgPHg/4BYHnQfQWQkJCQkLgSuVg5xyTg3wKDwFI57r8zzVJ6qaHj4fOaYD4A+1697uBFgnQA7r1A3sF4CfhGnb99cHoGhE+BTjX126C7DbhRuBvDfPZUN68mvNzwhoG3HL5pz/PCfjxx4NQb6unV9WCn+e3rSYutvec2tK5xrdZ/s3XIvrU5TYcRudYOOR73wq1FJ9R8YnPnWE/e6GWVlpD4q5gQSBaBiAD/YOnXVwwJAgJsngVEBLg810UpQRAQoPPcQESAzFNvYEZHIPOsNaUlLyfIc8+CfF44Xs/Tozy9AnmSz7kMAlueDl1Th3ZcFt153M1rECh5sqBHYClGFJqEYJ4bBNSIgJYvZAkJiSfCyjDVt7RpHA+eaFd5tLRAEMD6hsH93iJsX97vkMImLG7jOr7YuNBKDqCSwO1s2BCHLXXclPfcIC69GABfLYAvJ/CvN/wAtBbiIGLXv40AAAAASUVORK5CYII=" style= "width: 6%" />
        
        
        <?php elseif(is_a($exception, 'app\components\CustomException') && $exception->getCode() == 56001) : ?>
            <img alt="Ha ocurrido un error" src= "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAABzwAAAc8B4QwQxwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAMqSURBVHic7ZvPS1RRFMc/Z9JFOpQmZRC4iwKLwqywTYX9XCYRLWrXpk3b/opWgqAQLVrVwkWLIMhNUFlpQVBGkItQCqQiIct+eFrcNzXTNOO77/reHefOFw44zLnfe853fOe+e967oqr8CxHpA/qB3si2lDmtDswCE5GNqerDMg9V/WNAHhgGtE5tGMiX5FyUfA8wXQNBpm3TQE+JANEvH0LyxSLkiwWo53/7SjasqgjQBzwgTBzIYap9qOjPYZa5UNErwAyrd513xaxgCkKwyPkOwDcaAvgOwDcaAvgOwDcaAvgOwDeaEoz5ANx1mLMZGKjw3Sjww4H7CNBhO8h2FzVe3FCwNaCtCnebI/e4bT7BXwLBC5CkBnSIyNmizy9V9Xm1ASJyDNgQfWyp4jogIgvR3wqMqmrFmiAiGyndzltf/4WJktp3YFuMa/NiAu4bMXibgBeOOTgNvh6zOK0B3lty74jJfc4lB9ca8DiOk6r+AiYteL8CUzF9n1rwlsFVgLmUfOcj0eLgowVvGVwF2J2Sb6eIbE6BtwyuAhwXkWU5RKQL6LbkPrnCfhXhUgQVuByjUN1JwDsHbFqGdx/w0zF+ZwEWgUuA/CfAdcBVB+4nwPYKyR8G3rrGv5JN0fvALUxVzmPa7eeBLkfeb8A14BHmkVY3cAg4A4gjN42usO8AfKMhgO8AfCPJbnAlMIW5NZ4E3gG7gD3AXqA962Bcl0EbmwFOVFnXW4BBYCnDmDKb6CbQHnOH14/97rGmBXgGNFv2947WiwCLwM6ETc6hehDgikOXtxWYTzO+LJbBe0kHquoX7Bop1shCANcEVrUAn1R1xpGjasfZFWkLsF5E8o4cqb6/lLYAORxbVpg7xNSQRQ1wfQ0v9df40l4GXwMtCZfB0xnEl/oECgwmSL4T0xesCwGWgFMWya8FbmcUWyaTFGwIaF0m+f3Aq6xi8tETfAOM8Lcf8BnYiqn2B4ELmGeJmaAWmqILVH9knipqQQCvCL4nmMMcLQsVsznMmbpQMdEQABjzHYVHjBVuPoI9NlcQIOyDk5EI4R6dLRIhuMPTEiVegpCOz/8GBNhYmDYr1GQAAAAASUVORK5CYII=" style= "width: 6%" />


        <?php else : ?>
            <img alt="Ha ocurrido un error" src= "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEAAQMAAABmvDolAAAABlBMVEUAAAD///+l2Z/dAAAAAnRSTlP/AOW3MEoAAAAJcEhZcwAADnYAAA52ARTacF8AAAJcSURBVGgF7NbBkeQqDAbgn3LVcnQIhMKGspng0ByKQ/CRA8W/ZVTu7tEz6LL7Djt8p2FKkmXZpgHJgo5IEiR3dCwSUNGVWkBGV2gBB7p8C9jQ5UiwYiARLBiIBDMGAsETAyvBAwOe4I6BheCGAUcQQ0TFUELBULQDMoaCHXBiaLUDDgx5O2DH0PIHAjYMuf8j4HuYpmlyG/ADzS9ZKcsOrGhOWSn+AELLc1lWynoCseUtRVZKyEBqeb7KSokFYMtbKSslVTi2vMDtWmkkFra8yB0kFEduni0v8bhWegzkvkoeeV4rPQbyCC3Pkfla/bfCGe8KZdUVJC/dPdRWSyEL77tgJKElkvccSNbH4+k9SZIFiuTJs5BaiuTJ05RaiuTJ+yC1FMlDI7U09249SS3t3XqUWlp6tR6klhZfra9SSwuv1j2Z/0qA7sG+C3sO9iTtZ2E/Tft9sN8o+50032r7u7C/LPvbtL9ue3+wdxh7j7J3Ob1PmjutuVebu735e2H/4vyLpmmaSGIkkSzoW3k50MWmGgXIvd+ByHi28IZngaLbJl+ycQVW4wrkNriHbhOOHDfh+aEaLZBGC4/T5qXcf5ydKWzGwVvyUv9sv73v57HHjM5pT/8zPM3y87peVVAnVpn6BiV+Vk0PAYHUCx1Q8OJZoa2f03Us0PyXG0sF2vKlrZihLVUdmjSX9aFJ+5LjHgJ+4tOOb2+apt/V2kEJAEAMwDDm3/RJyKMwdrWRftBccJwNruqm1l2v22L3TRIqEdaMSwgmJROjydkCcZC6UJ6szzHAa4HnBO4ND2eUFfhSv8/iAAAAAElFTkSuQmCC" style= "width: 6%" />
        <?php endif; ?>



        <div class="alert alert-danger" style= "display: inline-block; width: 90%; margin-left: 10px; top: 10px; position: relative">

            <?php if(is_a($exception, 'yii\web\ForbiddenHttpException') || is_a($exception, 'yii\web\UnauthorizedHttpException')) : ?>
                <b>No tiene los permisos necesarios para visualizar esta página o realizar esta acción.</b><br>
                Contacte al administrador del sistema<br>


            <?php elseif(is_a($exception, 'yii\db\IntegrityException') && str_contains($exception->errorInfo[2], 'Duplicate entry')) : ?>
                <?php 
                    $matches = [];
                    $regex = '/Duplicate entry \'(\d+)-(\d+)\' for key \'(.+)\.ux_(.*)\'/m';
                    preg_match_all($regex, $exception->errorInfo[2], $matches, PREG_SET_ORDER, 0);

                    $valorDuplicado = StringHelper::endsWith($matches[0][4], 'empresa', false) || StringHelper::endsWith($matches[0][4], 'centro', false) ? $matches[0][1] : $matches[0][2];
                    // $tabla = $matches[0][3];
                ?>                
                <b>Algunos datos del formulario presentan problemas</b><br>
                El valor <?= Html::encode($valorDuplicado) ?> debe ser único<br>


            <?php elseif(is_a($exception, 'app\components\CustomException')) : ?>
                <?= Html::encode($exception->getMessage()) ?>


            <?php elseif(is_a($exception, 'yii\web\NotFoundHttpException')) : ?>
                <b>La página solicitada no existe.</b><br>
                La dirección <u><i><?= Html::encode(Yii::$app->request->url) ?> </i></u> no existe o no se encuentra disponible en este momento.


            <?php else : ?>
                <b>Ha ocurrido un error mientras el servidor web procesaba su solicitud.</b><br>
                Contacte al administrador del sistema<br>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- https://www.flaticon.com/packs/emoji-face-collection -->
    <div style= "margin-left: 5px; width: 90px; font-size: 9px; margin-bottom: 200px">
        Icon made by a <a href="https://www.flaticon.com/" title="Flaticon" target="_blank">Flaticon</a> <a href="https://www.flaticon.com/authors/alfredo-hernandez" title="Alfredo Hernandez" target="_blank">User</a>
    </div>

    <a href= "<?= Yii::$app->request->referrer ?>" class= "btn btn-sm btn-default">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>
    <a href= "/" class= "btn btn-sm btn-default">
        <i class="fas fa-home"></i> Inicio
    </a>
</div>
