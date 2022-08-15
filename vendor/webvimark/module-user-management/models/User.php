<?php

namespace webvimark\modules\UserManagement\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\ForbiddenHttpException;
use webvimark\helpers\LittleBigHelper;
use webvimark\helpers\Singleton;
use webvimark\modules\UserManagement\components\AuthHelper;
use webvimark\modules\UserManagement\components\UserIdentity;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\rbacDB\Route;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $confirmation_token
 * @property integer $status
 * @property integer $superadmin
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $registration_ip
 * @property string $bind_to_ip
 * @property string $email
 * @property integer $email_confirmed
 * @property integer $nombre
 * @property string $apellido_paterno
 * @property string $apellido_materno
 */
class User extends UserIdentity {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    //const STATUS_BANNED = -1;

    /**
     * @var string
     */
    public $gridRoleSearch;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $repeat_password;

    /**
     * Store result in singleton to prevent multiple db requests with multiple calls
     *
     * @param bool $fromSingleton
     *
     * @return static
     */
    public static function getCurrentUser($fromSingleton = true) {

        if (!$fromSingleton) {
            return static::findOne(Yii::$app->user->id);
        }

        $user = Singleton::getData('__currentUser');

        if (!$user) {
            $user = static::findOne(Yii::$app->user->id);
            Singleton::setData('__currentUser', $user);
        }

        return $user;
    }

    /**
     * Assign role to user
     *
     * @param int  $userId
     * @param string $roleName
     *
     * @return bool
     */
    public static function assignRole($userId, $roleName) {
        try {
            Yii::$app->db->createCommand()
                ->insert(Yii::$app->getModule('user-management')->auth_assignment_table, [
                    'user_id' => $userId,
                    'item_name' => $roleName,
                    'created_at' => time(),
                ])->execute();

            AuthHelper::invalidatePermissions();

            return true;
        } catch (\Exception $e) {
            throw new \yii\db\Exception('Error al asignar rol: ' . $e);
        }
    }

    /**
     * Revoke role from user
     *
     * @param int    $userId
     * @param string $roleName
     *
     * @return bool
     */
    public static function revokeRole($userId, $roleName) {
        $result = Yii::$app->db->createCommand()
            ->delete(Yii::$app->getModule('user-management')->auth_assignment_table, ['user_id' => $userId, 'item_name' => $roleName])
            ->execute() > 0;

        if ($result) {
            AuthHelper::invalidatePermissions();
        }

        return $result;
    }

    /**
     * @param string|array $roles
     * @param bool         $superAdminAllowed
     *
     * @return bool
     */
    public static function hasRole($roles, $superAdminAllowed = true) {
        if ($superAdminAllowed and Yii::$app->user->isSuperadmin) {
            return true;
        }
        $roles = (array)$roles;

        AuthHelper::ensurePermissionsUpToDate();

        return array_intersect($roles, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_ROLES, [])) !== [];
    }

    /**
     * @param string $permission
     * @param bool   $superAdminAllowed
     *
     * @return bool
     */
    public static function hasPermission($permission, $superAdminAllowed = true) {
        if ($superAdminAllowed and Yii::$app->user->isSuperadmin) {
            return true;
        }

        AuthHelper::ensurePermissionsUpToDate();

        return in_array($permission, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_PERMISSIONS, []));
    }

    public static function throwErrorIfNoPermision($permission, $superAdminAllowed = true) {
        if (!self::hasPermission($permission, $superAdminAllowed)) {
            throw new ForbiddenHttpException('No cuenta con los permisos para completar esta acción.');
        }
    }

    /**
     * Useful for Menu widget
     *
     * <example>
     * 	...
     * 		[ 'label'=>'Some label', 'url'=>['/site/index'], 'visible'=>User::canRoute(['/site/index']) ]
     * 	...
     * </example>
     *
     * @param string|array $route
     * @param bool         $superAdminAllowed
     *
     * @return bool
     */
    public static function canRoute($route, $superAdminAllowed = true) {
        if ($superAdminAllowed and Yii::$app->user->isSuperadmin) {
            return true;
        }

        $baseRoute = AuthHelper::unifyRoute($route);

        if (Route::isFreeAccess($baseRoute)) {
            return true;
        }

        AuthHelper::ensurePermissionsUpToDate();

        return Route::isRouteAllowed($baseRoute, Yii::$app->session->get(AuthHelper::SESSION_PREFIX_ROUTES, []));
    }

    /**
     * getStatusList
     * @return array
     */
    public static function getStatusList() {
        return array(
            self::STATUS_ACTIVE   => 'Activo',
            self::STATUS_INACTIVE => 'Inactivo',
        );
    }

    /**
     * getStatusValue
     *
     * @param string $val
     *
     * @return string
     */
    public static function getStatusValue($val) {
        $ar = self::getStatusList();
        return isset($ar[$val]) ? $ar[$val] : $val;
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return Yii::$app->getModule('user-management')->user_table;
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nombre'], 'required'],
            [['password', 'repeat_password'], 'required', 'on' => ['usuario', 'changePassword']],
            [['username', 'use_fktipo'], 'required', 'on' => ['usuario']],

            ['username', 'unique'],
            ['username', 'trim'],
            [['nombre', 'apellido_paterno', 'apellido_materno'], 'string', 'max' => 100],

            [['status', 'email_confirmed'], 'integer'],

            ['email', 'email'],

            ['bind_to_ip', 'validateBindToIp'],
            ['bind_to_ip', 'trim'],
            ['bind_to_ip', 'string', 'max' => 255],

            ['password', 'string', 'max' => 255, 'on' => ['usuario', 'changePassword']],
            ['password', 'trim', 'on' => ['usuario', 'changePassword']],
            ['password', 'match', 'pattern' => Yii::$app->getModule('user-management')->passwordRegexp],

            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Check that there is no such confirmed E-mail in the system
     */
    public function validateEmailConfirmedUnique() {
        if ($this->email) {
            $exists = User::findOne([
                'email'           => $this->email,
                'email_confirmed' => 1,
            ]);

            if ($exists and $exists->id != $this->id) {
                $this->addError('email', 'Correo electrónico ya ocupado');
            }
        }
    }

    /**
     * Validate bind_to_ip attr to be in correct format
     */
    public function validateBindToIp() {
        if ($this->bind_to_ip) {
            $ips = explode(',', $this->bind_to_ip);

            foreach ($ips as $ip) {
                if (!filter_var(trim($ip), FILTER_VALIDATE_IP)) {
                    $this->addError('bind_to_ip', 'Formato incorrecto. Ingrese múltiples IPs separando por comas.');
                }
            }
        }
    }

    /**
     * @return array
     */
    public function attributeLabels() {
        return [
            'id'                 => 'ID',
            'username'           => 'Usuario',
            'superadmin'         => 'Superusuario',
            'confirmation_token' => 'Token',
            'registration_ip'    => 'Dirección IP de registro',
            'bind_to_ip'         => 'Casar a IP',
            'status'             => 'Estatus',
            'gridRoleSearch'     => 'Roles',
            'created_at'         => 'Creado el',
            'updated_at'         => 'Actualizado el',
            'password'           => 'Contraseña',
            'repeat_password'    => 'Repetir contraseña',
            'email_confirmed'    => 'Correo confirmado',
            'email'              => 'Correo electrónico',
            'nombre'             => "Nombre",
            'apellido_paterno'   => "Apellido paterno",
            'apellido_materno'   => "Apellido materno"
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles() {
        return $this->hasMany(Role::class, ['name' => 'item_name'])
            ->viaTable(Yii::$app->getModule('user-management')->auth_assignment_table, ['user_id' => 'id']);
    }

    /**
     * Make sure user will not deactivate himself and superadmin could not demote himself
     * Also don't let non-superadmin edit superadmin
     *
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->created_at   =   time();
            $this->created_by   =   Yii::$app->user->identity->id;

            if (php_sapi_name() != 'cli') {
                $this->registration_ip = LittleBigHelper::getRealIp();
            }
            $this->generateAuthKey();
        } else {
            $this->updated_at   =   time();
            $this->updated_by   =   Yii::$app->user->identity->id;

            // Console doesn't have Yii::$app->user, so we skip it for console
            if (php_sapi_name() != 'cli') {
                if (Yii::$app->user->id == $this->id) {
                    // Make sure user will not deactivate himself
                    $this->status = static::STATUS_ACTIVE;

                    // Superadmin could not demote himself
                    if (Yii::$app->user->isSuperadmin and $this->superadmin != 1) {
                        $this->superadmin = 1;
                    }
                }

                // Don't let non-superadmin edit superadmin
                if (isset($this->oldAttributes['superadmin']) && !Yii::$app->user->isSuperadmin && $this->oldAttributes['superadmin'] == 1) {
                    return false;
                }
            }
        }

        // If password has been set, than create password hash
        if ($this->password) {
            $this->setPassword($this->password);
        }

        return parent::beforeSave($insert);
    }

    /**
     * Don't let delete yourself and don't let non-superadmin delete superadmin
     *
     * @inheritdoc
     */
    public function beforeDelete() {
        // Console doesn't have Yii::$app->user, so we skip it for console
        if (php_sapi_name() != 'cli') {
            // Don't let delete yourself
            if (Yii::$app->user->id == $this->id) {
                return false;
            }

            // Don't let non-superadmin delete superadmin
            if (!Yii::$app->user->isSuperadmin and $this->superadmin == 1) {
                return false;
            }
        }

        return parent::beforeDelete();
    }
}