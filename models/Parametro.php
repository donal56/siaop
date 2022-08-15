<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * This is the model class for table "parametro".
 *
 * @property int $id_parametro
 * @property string $codigo
 * @property string $nombre
 * @property string $tipo
 * @property string|null $valor_predeterminado
 * @property string|null $valor_predeterminado_empresa
 * @property int $unico
 * @property int $privado
 * @property int $requerido
 * @property int $activo
 * @property int $orden
 * @property string $fecha_version
 * @property int $usuario_version
 *
 * @property User $usuarioVersion
 * @property ParametroValor[] $parametrosValores
 * @property Empresa[] $empresa
 */
class Parametro extends \yii\db\ActiveRecord {

    public $pav_clave;
    public $pav_valor;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'parametros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['codigo', 'nombre', 'tipo', 'unico', 'privado', 'requerido', 'activo', 'usuario_version', 'orden'], 'required'],
            [['unico', 'privado', 'requerido', 'activo', 'usuario_version', 'pav_clave', 'id_parametro', 'orden'], 'integer'],
            [['fecha_version', 'pav_valor'], 'safe'],
            [['codigo'], 'string', 'max' => 10],
            [['nombre'], 'string', 'max' => 255],
            [['tipo'], 'string', 'max' => 10],
            [['valor_predeterminado', 'valor_predeterminado_empresa'], 'string', 'max' => 512],
            [['codigo'], 'unique'],
            [['usuario_version'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['usuario_version' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_parametro' => 'Clave',
            'codigo' => 'Código',
            'nombre' => 'Nombre',
            'tipo' => 'Tipo',
            'valor_predeterminado' => 'Valor predeterminado',
            'valor_predeterminado_empresa' => 'Valor predeterminado para usuarios multisocio',
            'unico' => 'Único',
            'privado' => 'Privado',
            'requerido' => 'Requerido',
            'activo' => 'Activo',
            'fecha_version' => 'Fecha-versión',
            'usuario_version' => 'Usuario-versión',
        ];
    }

    /**
     * Gets query for [[ParUsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioVersion() {
        return $this->hasOne(User::class, ['id' => 'usuario_version']);
    }

    /**
     * Gets query for [[ParametrosValores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParametrosValores() {
        return $this->hasMany(ParametrosValores::class, ['pav_fkpar' => 'id_parametro']);
    }

    /**
     * Gets query for [[PavFkemps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa() {
        return $this->hasMany(Empresas::class, ['emp_clave' => 'pav_fkemp'])->viaTable('parametros_valores', ['pav_fkpar' => 'id_parametro']);
    }
    
    public function getHtmlInput() {
        $name   = $this->codigo;
        $valor  = $this->pav_valor;
        
        $html = "";
        $opciones = [
            "class"     =>  "form-control", 
            "id"        =>  $name,
            "required"  =>  $this->requerido,
        ];

        if($this->privado && !Yii::$app->user->isSuperAdmin) {
            return "";
        }

        $html .= '<div class= "row"><div class= "form-group col-sm-8"><label class= "control-label">' 
                    . $this->nombre . 
                '</label>';

        $metaOptionsIndex = strpos($this->tipo, ":");
        $rawType = $metaOptionsIndex !== false ? substr($this->tipo, 0, $metaOptionsIndex) : $this->tipo;

        switch ($rawType) {
            case 'FILE':
                $opciones["required"] = !isset($valor);
                $html .= Html::fileInput($name . "-FILE", $valor, $opciones);
                $html .= Html::hiddenInput($name, $valor);
                $html .= '<small style= "margin-left: 10px">' . Html::a($valor, $valor, ['target' => '_blank'])  . '</small>';
                break;
            case 'DATE':
                $opciones["type"] = "date";
                $html .= Html::textInput($name, $valor, $opciones);
                break;
            case 'DATETIME':
                $opciones["type"] = "datetime-local";
                $html .= Html::textInput($name, $valor, $opciones);
                break;
            case 'TIME':
                $opciones["type"] = "time";
                $html .= Html::textInput($name, $valor, $opciones);
                break;
            case 'INT':
                $opciones["type"] = "number";
                $opciones["step"] = "1";
                $opciones["onkeypress"] = "return (event.charCode >= 48 && event.charCode <= 57)";
                $opciones["style"] = "width: 250px;";
                
                if($metaOptionsIndex !== false) {
                    $metaOptions = explode(":", substr($this->tipo, $metaOptionsIndex + 1));
                    $opciones["min"] = $metaOptions[0];
                    $opciones["max"] = $metaOptions[1];
                }

                $html .= Html::textInput($name, $valor, $opciones);
                break;
            case 'DEC':
                $opciones["type"] = "number";
                $opciones["step"] = "any";
                $html .= Html::textInput($name, $valor, $opciones);
                break;
            case 'BOOL':
                $opciones["class"] = null;
                $opciones["required"] = null;
                $opciones["value"] = '1';

                $html .= "&nbsp;&nbsp;&nbsp;";
                $html .= "<input type= 'hidden' name= '$name' value = '0'>";
                $html .= Html::checkbox($name, $valor === "1", $opciones);
                break;
            case 'COLOR':
                $opciones["type"] = "color";
                $opciones["style"] = "width: 40px; height: 40px; padding: 2px";
                $html .= Html::textInput($name, $valor, $opciones);
                break;
            case 'PASS':
                $opciones["type"] = "password";
                $html .= Html::textInput($name, $valor, $opciones);
                break;
            case 'COORD':
                $html .= Html::hiddenInput($name, $valor, $opciones);

                $latitud = explode(",", $valor)[0];
                $longitud = explode(",", $valor)[1];
                $html .= Html::tag('script', new JsExpression("GMap.createPinpointMap('$name', '$latitud', '$longitud')"));
                break;
            default:
                $html .= Html::textInput($name, $valor, $opciones);
        }

        $html .= "</div></div>";

        return $html;
    }

    public function beforeSave($insert) {
        $this->fecha_version     =   date('Y-m-d H:i:s');
        $this->usuario_version   =   Yii::$app->user->identity->id;

        return false;
        //return parent::beforeSave($insert);
    }

    public static function recuperarParametrosDeLaEmpresa($idEmpresa = null, $asArray = false) {

        if(!isset($idEmpresa)) {
            if(Yii::$app->user->esMultiempresa()) {
                return Parametro::find()->asArray($asArray)->all();
            }
            else {
                $idEmpresa = Yii::$app->user->getEmpresaPredeterminada();
            }
        }

        $query = new Query();
        $query->from('vw_parametros')
            ->where(['id_empresa' => $idEmpresa])
            ->orderBy(['orden' => SORT_ASC]);

        $parametrosRS   =   $query->all();

        if($asArray) {
            return $parametrosRS;
        }

        $parametros     =   array();

        foreach ($parametrosRS as $parametroRS) {
            $param = new Parametro();
            $param->load(["Parametros" => $parametroRS]);
            array_push($parametros, $param);
        }

        return $parametros;
    }

    /**
     * @param String codigo - El código del parametro
     * @param Integer|Object contexto - 
     *      El contexto del que se extraera el socio comercial. Puede ser un objeto ActiveRecord de tipo(s) {OrdenesServicio, Reportes, User} 
     *      o un tipo Integer explicito. En el caso de la declaracion explicita del socio comercial solo sera válido si se tienen 
     *      permisos de superusuario. En caso de que sea nulo o las condiciones anteriores no apliquen se usara el valor predeterminado del parametro
     *      si es usuario multiempresa.
     * @return mixed - Depende del tipo de parametro. Los casos especiales son 'FILE' que devuleve la ruta y 'COORD' que devuelve un arreglo donde [0] es la latitud y [1] la longitud
     */
    public static function leerParametroDeLaEmpresa(string $codigo, $contexto = null) {

        $idEmpresa = null;
		$query = new Query();

        if(isset($contexto)) {
            if(is_int($contexto) && Yii::$app->user->isSuperAdmin) {
                $idEmpresa = $contexto;
            }
            else if(is_a($contexto, 'app\models\OrdenesServicio') || is_a($contexto, 'app\models\Reportes')) {
                $idEmpresa = $contexto->centro->cen_fkemp;
            }
            else if(is_a($contexto, 'app\models\Rutas')) {
                $idEmpresa = $contexto->rta_fkemp;
            }
            else if(is_a($contexto, 'webvimark\modules\UserManagement\models\User')) {
                $idEmpresa = $contexto->usuariosCentros[0]->centro->empresa->emp_clave;
            }
            else {
                die('No hay implementacion de contexto de parámetro para ' . $contexto::className);
            }
        }

        if(isset($idEmpresa)) {
            $query->from('vw_parametros')->where([
				'id_empresa' => $idEmpresa, 
				'codigo' => $codigo
			]);
        }
		else {
			if(Yii::$app->user->esMultiempresa()) {
				$query->select(['*', 'valor_predeterminado_empresa AS pav_valor'])
					->from('parametros')
					->where(['codigo' => $codigo]);
			}
			else {
				$query->from('vw_parametros')->where([
					'emp_clave' => Yii::$app->user->getEmpresaPredeterminada(), 
					'codigo' => $codigo
				]);
			}
		}

		$parametroRS = $query->one();

		switch ($parametroRS['tipo']) {
			case 'FILE':
				return substr($parametroRS['pav_valor'], 1, strlen($parametroRS['pav_valor']) - 1);
			case 'INT':
				return intval($parametroRS['pav_valor']);
			case 'DEC':
				return floatval($parametroRS['pav_valor']);
			case 'BOOL':
				return filter_var($parametroRS['pav_valor'], FILTER_VALIDATE_BOOLEAN);
			case 'COORD':
				return explode(",", $parametroRS['pav_valor']);
			default:
				return $parametroRS['pav_valor'];
		}
    }
}
