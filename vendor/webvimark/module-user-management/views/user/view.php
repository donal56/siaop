<?php

use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */

$this->title = $model->username;

?>
<div class="user-view">

    <h2 class="lte-hide-title"><?= $this->title ?></h2>
    <p>
        <?php
        if (User::hasPermission('editUsers')) {
            echo Html::a('Actualizar', ['/user-management/user/update', 'id' => $model->id], ['class' => 'btn btn-primary']) . " ";
        }

        if (User::hasPermission('createUsers')) {
            echo Html::a('Crear usuario', ['/user-management/user/create'], ['class' => 'btn btn-success']) . ' ';
        }

        if (User::hasPermission('agregarEmpleados')) {
            echo Html::a('Agregar empleado', ['/user-management/user/create'], ['class' => 'btn btn-success']) . ' ';
        }

        echo Html::a('Regresar', ['/user-management/user/index'], ['class' => 'btn btn-default']);
        ?>
    </p>

    <br>

    <div class="panel panel-default">
        <div class="panel-body">

            <?= DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    [
                        'attribute' => 'status',
                        'value' => User::getStatusValue($model->status),
                    ],
                    'use_nombre',
                    'username',
                    [
                        'attribute' => 'email',
                        'value' => $model->email,
                        'format' => 'email',
                    ],
                    [
                        'label' => 'Roles',
                        'value' => implode('<br>', ArrayHelper::map(Role::getUserRoles($model->id), 'name', 'description')),
                        'visible' => User::hasPermission('viewUserRoles'),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'bind_to_ip',
                        'visible' => User::hasPermission('bindUserToIp'),
                    ],
                    [
                        'attribute' => 'registration_ip',
                        'value' => Html::a($model->registration_ip, "http://ipinfo.io/" . $model->registration_ip, ["target" => "_blank"]),
                        'format' => 'raw',
                        'visible' => User::hasPermission('viewRegistrationIp'),
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>

        </div>
    </div>
</div>