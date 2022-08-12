<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\JsExpression;

/**
 * This is the model class for table "parametros".
 *
 * @property int $par_clave
 * @property string $par_codigo
 * @property string $par_nombre
 * @property string $par_tipo
 * @property string|null $par_valorPredeterminado
 * @property string|null $par_valorPredeterminadoMultiEmpresa
 * @property int $par_unico
 * @property int $par_privado
 * @property int $par_requerido
 * @property int $par_activo
 * @property int $par_orden
 * @property string $par_fechaVersion
 * @property int $par_usuarioVersion
 *
 * @property User $parUsuarioVersion
 * @property ParametrosValores[] $parametrosValores
 * @property Empresas[] $pavFkemps
 */
class Parametros extends \yii\db\ActiveRecord
{
    public $pav_clave;
    public $pav_valor;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parametros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['par_codigo', 'par_nombre', 'par_tipo', 'par_unico', 'par_privado', 'par_requerido', 'par_activo', 'par_usuarioVersion', 'par_orden'], 'required'],
            [['par_unico', 'par_privado', 'par_requerido', 'par_activo', 'par_usuarioVersion', 'pav_clave', 'par_clave', 'par_orden'], 'integer'],
            [['par_fechaVersion', 'pav_valor'], 'safe'],
            [['par_codigo'], 'string', 'max' => 10],
            [['par_nombre'], 'string', 'max' => 255],
            [['par_tipo'], 'string', 'max' => 10],
            [['par_valorPredeterminado', 'par_valorPredeterminadoMultiEmpresa'], 'string', 'max' => 512],
            [['par_codigo'], 'unique'],
            [['par_usuarioVersion'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['par_usuarioVersion' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'par_clave' => 'Clave',
            'par_codigo' => 'Código',
            'par_nombre' => 'Nombre',
            'par_tipo' => 'Tipo',
            'par_valorPredeterminado' => 'Valor predeterminado',
            'par_valorPredeterminadoMultiEmpresa' => 'Valor predeterminado para usuarios multisocio',
            'par_unico' => 'Único',
            'par_privado' => 'Privado',
            'par_requerido' => 'Requerido',
            'par_activo' => 'Activo',
            'par_fechaVersion' => 'Fecha-versión',
            'par_usuarioVersion' => 'Usuario-versión',
        ];
    }

    /**
     * Gets query for [[ParUsuarioVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParUsuarioVersion()
    {
        return $this->hasOne(User::class, ['id' => 'par_usuarioVersion']);
    }

    /**
     * Gets query for [[ParametrosValores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParametrosValores()
    {
        return $this->hasMany(ParametrosValores::class, ['pav_fkpar' => 'par_clave']);
    }

    /**
     * Gets query for [[PavFkemps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPavFkemps()
    {
        return $this->hasMany(Empresas::class, ['emp_clave' => 'pav_fkemp'])->viaTable('parametros_valores', ['pav_fkpar' => 'par_clave']);
    }
    
    public function getHtmlInput() {
        $name   = $this->par_codigo;
        $valor  = $this->pav_valor;
        
        $html = "";
        $opciones = [
            "class"     =>  "form-control", 
            "id"        =>  $name,
            "required"  =>  $this->par_requerido,
        ];

        if($this->par_privado && !Yii::$app->user->isSuperAdmin) {
            return "";
        }

        $html .= '<div class= "row"><div class= "form-group col-sm-8"><label class= "control-label">' 
                    . $this->par_nombre . 
                '</label>';

        $metaOptionsIndex = strpos($this->par_tipo, ":");
        $rawType = $metaOptionsIndex !== false ? substr($this->par_tipo, 0, $metaOptionsIndex) : $this->par_tipo;

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
                    $metaOptions = explode(":", substr($this->par_tipo, $metaOptionsIndex + 1));
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
        $this->par_fechaVersion     =   date('Y-m-d H:i:s');
        $this->par_usuarioVersion   =   Yii::$app->user->identity->id;

        return false;
        //return parent::beforeSave($insert);
    }

    public static function recuperarParametrosDeLaEmpresa($idEmpresa = null, $asArray = false) {

        if(!isset($idEmpresa)) {
            if(Yii::$app->user->esMultiempresa()) {
                return Parametros::find()->asArray($asArray)->all();
            }
            else {
                $idEmpresa = Yii::$app->user->getEmpresaPredeterminada();
            }
        }

        $query = new Query();
        $query->from('vw_parametros')
            ->where(['emp_clave' => $idEmpresa])
            ->orderBy(['par_orden' => SORT_ASC]);

        $parametrosRS   =   $query->all();

        if($asArray) {
            return $parametrosRS;
        }

        $parametros     =   array();

        foreach ($parametrosRS as $parametroRS) {
            $param = new Parametros();
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
				'emp_clave' => $idEmpresa, 
				'par_codigo' => $codigo
			]);
        }
		else {
			if(Yii::$app->user->esMultiempresa()) {
				$query->select(['*', 'par_valorPredeterminadoMultiEmpresa AS pav_valor'])
					->from('parametros')
					->where(['par_codigo' => $codigo]);
			}
			else {
				$query->from('vw_parametros')->where([
					'emp_clave' => Yii::$app->user->getEmpresaPredeterminada(), 
					'par_codigo' => $codigo
				]);
			}
		}

		$parametroRS   =   $query->one();

		switch ($parametroRS['par_tipo']) {
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
