<?php

namespace webvimark\modules\UserManagement\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use webvimark\components\AdminDefaultController;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\models\search\UserSearch;
use webvimark\modules\UserManagement\models\rbacDB\Role;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminDefaultController {
    /**
     * @var User
     */
    public $modelClass = 'webvimark\modules\UserManagement\models\User';

    /**
     * @var UserSearch
     */
    public $modelSearchClass = 'webvimark\modules\UserManagement\models\search\UserSearch';

    /**
     * @return mixed|string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new User(['scenario' => 'usuario']);

        if ($model->load(Yii::$app->request->post())) {

            $transaction = \Yii::$app->db->beginTransaction();

            if (!User::hasPermission("editUserEmail")) {
                $model->email = null;
                $model->email_confirmed = 0;
            } else {
                $model->email_confirmed = 1;
            }

            if ($model->save()) {
                if ($transaction->getIsActive()) {
                    $transaction->commit();
                    return $this->redirect(['index']);
                }
            }
            $transaction->rollback();
        }

        return $this->renderIsAjax('create', compact('model'));
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($this->scenarioOnUpdate) {
            $model->scenario = $this->scenarioOnUpdate;
        }

        if ($model->load(Yii::$app->request->post())) {
            $roles = ArrayHelper::map(Role::getUserRoles($model->id), 'name', 'description');

            $transaction = \Yii::$app->db->beginTransaction();

            if (!User::hasPermission("editUserEmail")) {
                $model->email = null;
                $model->email_confirmed = 0;
            } else {
                $model->email_confirmed = 1;
            }

            if ($model->save()) {
                foreach ($roles as $role) {
                    User::revokeRole($model->id, $role);
                }
            } else {
                $transaction->rollback();
            }

            if ($transaction->getIsActive()) {
                $transaction->commit();
                $redirect = $this->getRedirectPage('update', $model);
                return $redirect === false ? '' : $this->redirect($redirect);
            }
        }

        return $this->renderIsAjax('update', compact('model'));
    }

    /**
     * @param int $id User ID
     *
     * @throws \yii\web\NotFoundHttpException
     * @return string
     */
    public function actionChangePassword($id) {
        $model = User::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $model->scenario = 'changePassword';

        if (User::hasPermission("changeUserPassword") && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view',    'id' => $model->id]);
        }

        return $this->renderIsAjax('changePassword', compact('model'));
    }

    public function findModel($id) {
        $model = User::findOne(["user.id" => $id]);

        if ($model !== null) {
            return $model;
        }

        throw new UnauthorizedHttpException('No autorizado.');
    }
}
