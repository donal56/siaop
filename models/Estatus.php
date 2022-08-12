<?php

namespace app\models;

use Yii;

class Estatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['est_nombre'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'est_clave' => 'Clave',
            'est_nombre' => ' Nombre',
        ];
    }

    public static function getComboOptions(int $soloFinalizadPendientes = 0) {

        $estatus = [
            [0, '<span class= "far fa-hourglass"></span> Pendiente', 'warning', 'Pendiente'],
            [1, '<span class= "fa fa-check"></span> Finalizada', 'success', 'Finalizada'],
        ];
        
        if($soloFinalizadPendientes === 0) {
            $estatus[] = [2, '<span class= "fa fa-ban"></span> Cancelada', 'danger', 'Cancelada'];
            $estatus[] = [3, '<span class= "fas fa-check-double"></span> Aprobada', 'info', 'Aprobada'];
            $estatus[] = [4, '<span class= "fas fa-frown"></span> No ejecutada', 'default', 'No ejecutada'];
            $estatus[] = [5, '<span class= "fas fa-frown"></span> Pendiente con errores', 'primary', 'Pendiente con errores'];
        }
        
        if(Yii::$app->user->getIsCliente()) {
            $estatus = [];
            
            if(Parametros::leerParametroDeLaEmpresa("CTE_PENDT")) {
                $estatus[] = [0, '<span class= "far fa-hourglass"></span> Pendiente', 'warning', 'Pendiente'];
                $estatus[] = [5, '<span class= "fas fa-frown"></span> Pendiente con errores', 'primary', 'Pendiente con errores'];
            }
        
            if(Parametros::leerParametroDeLaEmpresa("CTE_FINALZ")) {
                $estatus[] = [1, '<span class= "fa fa-check"></span> Finalizada', 'success', 'Finalizada'];
            }
            
            if(Parametros::leerParametroDeLaEmpresa("CTE_CANCEL")) {
                $estatus[] = [2, '<span class= "fa fa-ban"></span> Cancelada', 'danger', 'Cancelada'];
            }
            
            if(Parametros::leerParametroDeLaEmpresa("CTE_APROB")) {
                $estatus[] = [3, '<span class= "fas fa-check-double"></span> Aprobada', 'info', 'Aprobada'];
            }
        
            if(Parametros::leerParametroDeLaEmpresa("CTE_IMPROD")) {
                $estatus[] = [4, '<span class= "fas fa-frown"></span> No ejecutada', 'default', 'No ejecutada'];
            }
        }

        return $estatus;
    }
}
