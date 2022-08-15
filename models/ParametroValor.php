<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "parametros_valores".
 *
 * @property int $id_parametro_valor
 * @property int $activo
 * @property string|null $valor
 * @property int $id_parametro
 * @property int $id_empresa
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property User $pavUsuarioVersion
 * @property Empresas $pavFkemp
 * @property Parametros $pavFkpar
 */
class ParametroValor extends \yii\db\ActiveRecord {

    public $parametro;
    public $valor_anterior;

    public function __construct($id, Parametro $parametro, string $valor, int $idEmpresa) {

        $parametro->privado     =   filter_var($parametro->privado, FILTER_VALIDATE_BOOLEAN);
        $parametro->requerido   =   filter_var($parametro->requerido, FILTER_VALIDATE_BOOLEAN);
        $parametro->unico       =   filter_var($parametro->unico, FILTER_VALIDATE_BOOLEAN);
        $parametro->activo      =   filter_var($parametro->activo, FILTER_VALIDATE_BOOLEAN);

        $this->id_parametro_valor = $id;
        $this->valor = $valor;
        $this->id_empresa = $idEmpresa;
        $this->id_parametro = $parametro->par_clave;
        $this->parametro = $parametro;
        $this->valor_anterior = $parametro->valor;

        $this->setIsNewRecord(!isset($id));
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'parametros_valores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_parametro'], 'required'],
            [['activo', 'id_parametro', 'id_empresa', 'usuario_version'], 'integer'],
            [['fecha_version'], 'safe'],
            [['valor'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_parametro_valor' => 'Clave',
            'activo' => 'Activo',
            'valor' => 'Valor',
            'id_parametro' => 'Parámetro',
            'id_empresa' => 'Socio comercial',
            'fecha_version' => 'Fecha-versión',
            'usuario_version' => 'Usuario-versión',
        ];
    }

    /**
     * Gets query for [[PavUsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsuarioVersion() {
        return $this->hasOne(User::class, ['id' => 'usuario_version']);
    }

    /**
     * Gets query for [[PavFkemp]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getEmpresa() {
        return $this->hasOne(Empresas::class, ['emp_clave' => 'id_empresa']);
    }

    /**
     * Gets query for [[PavFkpar]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getParametro() {
        return $this->hasOne(Parametros::class, ['par_clave' => 'id_parametro']);
    }

    public function beforeSave($insert) {

        $this->fecha_version     =   date('Y-m-d H:i:s');
        $this->usuario_version   =   Yii::$app->user->identity->id;
        $this->activo           =   1;

        $this->markAttributeDirty('valor');

        if ($this->parametro->par_tipo === "FILE" && isset($this->valor)) {
            $ruta       =   Yii::getAlias('@webroot') . $this->valor;
            $rutaDir    =   substr($ruta, 0, strrpos($ruta, "/") + 1);

            if (!is_dir($rutaDir)) {
                if (!mkdir($rutaDir, 0777, true)) {
                    $this->addError("parametro", "No se pudo crear el directorio");
                }
            }

            $conflictedFiles = glob($rutaDir . $this->id_empresa . '.*');

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

        if (!isset($this->parametro) || !isset($this->parametro->tipo) || !isset($this->parametro->codigo)) {
            $this->addError("parametro", "No se pudo recuperar configuración del parametro");
            return false;
        }

        if ($this->parametro->privado && !Yii::$app->user->isSuperAdmin) {
            $this->addError("privado", "Parámetro privado");
            return false;
        }

        if ($this->valor === $this->valor_anterior && $this->parametro->tipo !== "FILE") {
            return false;
        }

        if ($this->parametro->requerido) {
            if (!isset($this->valor)) {
                if (isset($this->parametro->valorPredeterminado)) {
                    $this->valor = $this->parametro->valorPredeterminado;
                } else if ($this->parametro->tipo !== "FILE") {
                    $this->addError("valor", "Valor requerido");
                    return false;
                }
            }
        }

        //Validación y limpieza
        switch ($this->parametro->tipo) {
            case 'TEXT':
                $this->valor = trim(strval($this->valor));
                break;
            case 'BOOL':
                $this->valor = filter_var($this->valor, FILTER_VALIDATE_BOOLEAN);
                $this->valor = $this->valor ? "1" : "0";
                break;
            case 'INT':
                $this->valor = intval($this->valor);
                $this->valor = strval($this->valor);
                break;
            case 'DEC':
                $this->valor = floatval($this->valor);
                $this->valor = strval($this->valor);
                break;
            case 'DATE':
                $this->valor = \DateTime::createFromFormat('Y-m-d', $this->valor)->format('Y-m-d');
                break;
            case 'DATETIME':
                $this->valor = \DateTime::createFromFormat('Y-m-d\TH:i', $this->valor)->format('Y-m-d\TH:i');
                break;
            case 'TIME':
                $this->valor = \DateTime::createFromFormat('H:i', $this->valor)->format('H:i');
                break;
            case 'COORD':
                $coords = explode(",", $this->valor);
                if(!empty($this->valor) && sizeof($coords) != 2) {
                    $this->addError("valor", "Coordinadas no válidas");
                }
                break;
            case 'FILE':
                $fileData   =   $_FILES[$this->parametro->par_codigo . "-FILE"];

                if ($fileData['tmp_name']) {
                    $ext = strtolower(pathinfo(basename($fileData['name']))['extension']);
                    $this->valor = "/img/parametros/" . mb_strtoupper($this->parametro->par_codigo)
                        . "/" . $this->id_empresa . "." . $ext;
                } else {
                    //No guardar imagen
                    if ($this->parametro->requerido) {
                        $this->addError("valor", "Archivo requerido");
                    }
                    $this->valor = null;
                    return false;
                }

                break;
            case 'COLOR':
                $matches = [];
                $regex = '/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/m';
                preg_match_all($regex, $this->valor, $matches, PREG_SET_ORDER, 0);

                if (!isset($matches[0])) {
                    $this->addError("valor", "El valor introducido no coincide con el tipo esperado");
                    return false;
                }

                break;
            case 'PASS':
        }

        if ($this->parametro->unico) {
            $query = new Query();
            $query->from('vw_parametros')->where([
                'codigo' => $this->parametro->par_codigo, 
                'valor' => $this->valor
            ])->andWhere(['<>', 'id_parametro_valor', $this->id_parametro_valor]);
            $count = intval($query->count()) === 0;

            if (!$count) {
                $this->addError("valor", "El valor debe ser único entre empresas");
                return false;
            }
        }

        return true;
    }
}
