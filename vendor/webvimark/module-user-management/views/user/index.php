<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Empresas;
use app\models\Centros;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var webvimark\modules\UserManagement\models\search\UserSearch $searchModel
 */

$this->title = 'Usuarios';
?>

<div class="user-index">

    <h2 class="lte-hide-title"><?= $this->title ?></h2>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row m-3"> 
                <div class="col-sm-3">
                    <?php
                        if (User::hasPermission('createUsers')) {
                            echo Html::a('<span class="glyphicon glyphicon-plus-sign"></span> Crear usuario', ['/user-management/user/create'], ['class' => 'btn btn-success mr-4']);
                        }
                    ?>
                </div>
            </div>

            <?= GridView::widget([
                'id' => 'user-grid',
                'dataProvider' => $dataProvider,
                'pager' => [
                    'options' => ['class' => 'pagination'],
                    'hideOnSinglePage' => true,
                    'lastPageLabel' => '>>',
                    'firstPageLabel' => '<<',
                ],
                'filterModel' => $searchModel,
                'layout' => '{items}<div class="row"><div class="col-sm-8">{pager}</div></div>',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'options' => ['style' => 'width:30px']],
                    'nombre',
                    'apellido_paterno',
                    'apellido_materno',
                    [
                        'attribute' => 'username',
                        'value' => function (User $model) {
                            return Html::a($model->username, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'email',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $e = $model->email;
                            return $e ? "<span class= 'glyphicon glyphicon-envelope' title= '" . $e . "'></span>&nbsp;<span class= 'glyphicon glyphicon-ok' style= 'color: green'></span>" : "";
                        },
                        'label' => 'E-mail',
                        'options' => ['style' => 'width: 100px; text-align: center'],
                        'filter' => false,
                        'visible' => User::hasPermission('viewUserEmail'),
                    ],
                    [
                        'value' => function (User $model) {
                            return GhostHtml::a(
                                "Roles y <br> permisos",
                                ['/user-management/user-permission/set', 'id' => $model->id],
                                ['class' => 'btn btn-sm btn-primary', 'data-pjax' => 0]
                            );
                        },
                        'format' => 'raw',
                        'visible' => Yii::$app->user->isSuperAdmin,
                        'options' => ['width' => '150px'],
                    ],
                    [
                        'value' => function (User $model) {
                            return GhostHtml::a(
                                "Cambiar <br> contraseña",
                                ['change-password', 'id' => $model->id],
                                ['class' => 'btn btn-sm btn-info', 'data-pjax' => 0]
                            );
                        },
                        'format' => 'raw',
                        'visible' => User::hasPermission('changeUserPassword')
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['style' => 'width:100px; text-align:center;'],
                        'visible' => User::hasPermission('viewUsers') || User::hasPermission('editUsers') || User::hasPermission('deleteUsers'),
                        'visibleButtons' => [
                            'view' => User::hasPermission('viewUsers'),
                            'update' => User::hasPermission('editUsers'),
                            'delete' => User::hasPermission('deleteUsers')
                        ],
                    ],
                ],
                'tableOptions' =>
                [
                    'class' => 'table table-striped table-hover table-responsive',
                ]
            ]); ?>
        </div>
    </div>