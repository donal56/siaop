<?php

namespace app\models;

use Exception;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "parametros_valores".
 *
 * @property int $pav_clave
 * @property int $pav_activo
 * @property string|null $pav_valor
 * @property int $pav_fkpar
 * @property int $pav_fkemp
 * @property string $pav_fechaVersion
 * @property int $pav_usuarioVersion
 *
 * @property User $pavUsuarioVersion
 * @property Empresas $pavFkemp
 * @property Parametros $pavFkpar
 */
class ParametrosValores extends \yii\db\ActiveRecord
{
    public $parametro;
    public $valor_anterior;

    public function __construct($id, Parametros $parametro, string $valor, int $idEmpresa)
    {

        $parametro->par_privado     =   filter_var($parametro->par_privado, FILTER_VALIDATE_BOOLEAN);
        $parametro->par_requerido   =   filter_var($parametro->par_requerido, FILTER_VALIDATE_BOOLEAN);
        $parametro->par_unico       =   filter_var($parametro->par_unico, FILTER_VALIDATE_BOOLEAN);
        $parametro->par_activo      =   filter_var($parametro->par_activo, FILTER_VALIDATE_BOOLEAN);

        $this->pav_clave = $id;
        $this->pav_valor = $valor;
        $this->pav_fkemp = $idEmpresa;
        $this->pav_fkpar = $parametro->par_clave;
        $this->parametro = $parametro;
        $this->valor_anterior = $parametro->pav_valor;

        $this->setIsNewRecord(!isset($id));
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parametros_valores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pav_fkpar'], 'required'],
            [['pav_activo', 'pav_fkpar', 'pav_fkemp', 'pav_usuarioVersion'], 'integer'],
            [['pav_fechaVersion'], 'safe'],
            [['pav_valor'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pav_clave' => 'Clave',
            'pav_activo' => 'Activo',
            'pav_valor' => 'Valor',
            'pav_fkpar' => 'Parámetro',
            'pav_fkemp' => 'Socio comercial',
            'pav_fechaVersion' => 'Fecha-versión',
            'pav_usuarioVersion' => 'Usuario-versión',
        ];
    }

    /**
     * Gets query for [[PavUsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getPavUsuarioVersion()
    {
        return $this->hasOne(User::class, ['id' => 'pav_usuarioVersion']);
    }

    /**
     * Gets query for [[PavFkemp]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getPavFkemp()
    {
        return $this->hasOne(Empresas::class, ['emp_clave' => 'pav_fkemp']);
    }

    /**
     * Gets query for [[PavFkpar]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getPavFkpar()
    {
        return $this->hasOne(Parametros::class, ['par_clave' => 'pav_fkpar']);
    }

    public function beforeSave($insert)
    {
        $this->pav_fechaVersion     =   date('Y-m-d H:i:s');
        $this->pav_usuarioVersion   =   Yii::$app->user->identity->id;
        $this->pav_activo           =   1;

        $this->markAttributeDirty('pav_valor');

        if ($this->parametro->par_tipo === "FILE" && isset($this->pav_valor)) {
            $ruta       =   Yii::getAlias('@webroot') . $this->pav_valor;
            $rutaDir    =   substr($ruta, 0, strrpos($ruta, "/") + 1);

            if (!is_dir($rutaDir)) {
                if (!mkdir($rutaDir, 0777, true)) {
                    $this->addError("parametro", "No se pudo crear el directorio");
                }
            }

            $conflictedFiles = glob($rutaDir . $this->pav_fkemp . '.*');

            if (!move_uploaded_file($_FILES[$this->parametro->par_codigo . "-FILE"]["tmp_name"], $ruta)) {
                $this->addError("parametro", "No se pudo guardar el archivo");
                return false;
            }

            if ($conflictedFiles) {
                foreach ($conflictedFiles as $conflictedFile) {
                    unlink($conflictedFile);
                }
            }
        }

        return parent::beforeSave($insert);
    }

    public function validar()
    {

        if (!isset($this->parametro) || !isset($this->parametro->par_tipo) || !isset($this->parametro->par_codigo)) {
            $this->addError("parametro", "No se pudo recuperar configuración del parametro");
            return false;
        }

        if ($this->parametro->par_privado && !Yii::$app->user->isSuperAdmin) {
            $this->addError("par_privado", "Parámetro privado");
            return false;
        }

        if ($this->pav_valor === $this->valor_anterior && $this->parametro->par_tipo !== "FILE") {
            return false;
        }

        if ($this->parametro->par_requerido) {
            if (!isset($this->pav_valor)) {
                if (isset($this->parametro->par_valorPredeterminado)) {
                    $this->pav_valor = $this->parametro->par_valorPredeterminado;
                } else if ($this->parametro->par_tipo !== "FILE") {
                    $this->addError("pav_valor", "Valor requerido");
                    return false;
                }
            }
        }

        //Validación y limpieza
        switch ($this->parametro->par_tipo) {
            case 'TEXT':
                $this->pav_valor = trim(strval($this->pav_valor));
                break;
            case 'BOOL':
                $this->pav_valor = filter_var($this->pav_valor, FILTER_VALIDATE_BOOLEAN);
                $this->pav_valor = $this->pav_valor ? "1" : "0";
                break;
            case 'INT':
                $this->pav_valor = intval($this->pav_valor);
                $this->pav_valor = strval($this->pav_valor);
                break;
            case 'DEC':
                $this->pav_valor = floatval($this->pav_valor);
                $this->pav_valor = strval($this->pav_valor);
                break;
            case 'DATE':
                $this->pav_valor = \DateTime::createFromFormat('Y-m-d', $this->pav_valor)->format('Y-m-d');
                break;
            case 'DATETIME':
                $this->pav_valor = \DateTime::createFromFormat('Y-m-d\TH:i', $this->pav_valor)->format('Y-m-d\TH:i');
                break;
            case 'TIME':
                $this->pav_valor = \DateTime::createFromFormat('H:i', $this->pav_valor)->format('H:i');
                break;
            case 'COORD':
                $coords = explode(",", $this->pav_valor);
                if(!empty($this->pav_valor) && sizeof($coords) != 2) {
                    $this->addError("pav_valor", "Coordinadas no válidas");
                }
                break;
            case 'FILE':
                $fileData   =   $_FILES[$this->parametro->par_codigo . "-FILE"];

                if ($fileData['tmp_name']) {
                    $ext = strtolower(pathinfo(basename($fileData['name']))['extension']);
                    $this->pav_valor = "/img/parametros/" . mb_strtoupper($this->parametro->par_codigo)
                        . "/" . $this->pav_fkemp . "." . $ext;
                } else {
                    //No guardar imagen
                    if ($this->parametro->par_requerido) {
                        $this->addError("pav_valor", "Archivo requerido");
                    }
                    $this->pav_valor = null;
                    return false;
                }

                break;
            case 'COLOR':
                $matches = [];
                $regex = '/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/m';
                preg_match_all($regex, $this->pav_valor, $matches, PREG_SET_ORDER, 0);

                if (!isset($matches[0])) {
                    $this->addError("pav_valor", "El valor introducido no coincide con el tipo esperado");
                    return false;
                }

                break;
            case 'PASS':
        }

        if ($this->parametro->par_unico) {
            $query = new Query();
            $query->from('vw_parametros')->where(['par_codigo' => $this->parametro->par_codigo, 'pav_valor' => $this->pav_valor])->andWhere(['<>', 'pav_clave', $this->pav_clave]);
            $count = intval($query->count()) === 0;

            if (!$count) {
                $this->addError("pav_valor", "El valor debe ser único entre empresas");
                return false;
            }
        }

        return true;
    }
}
