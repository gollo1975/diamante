<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormLicencia extends Model
{        
   
    public $id_licencia_pk;
    public $codigo_licencia;
    public $id_empleado;
    public $fecha_desde ;
    public $fecha_hasta;
    public $fecha_aplicacion;
    public $afecta_transporte;
    public $cobrar_administradora;
    public $aplicar_adicional;
    public $pagar_empleado;
    public $pagar_parafiscal;
    public $pagar_arl;
    public $observacion;





    public function rules()
    {
        return [            
               [['codigo_licencia', 'id_empleado', 'fecha_desde', 'fecha_hasta', 'fecha_aplicacion'], 'required'],
               [['codigo_licencia', 'id_empleado', 'afecta_transporte','cobrar_administradora','aplicar_adicional','pagar_empleado','pagar_arl','pagar_parafiscal'], 'integer'],
               [['fecha_desde', 'fecha_hasta','fecha_aplicacion'], 'safe'],
               [['observacion'], 'string', 'max' => 200],
           ];
    }

    public function attributeLabels()
    {
        return [   
            'codigo_licencia' => 'Tipo licencia',
            'id_empleado' => 'Empleado(a)',
            'fecha_inicio' => 'Fecha desde',
            'fecha_final' => 'Fecha hasta',
            'dias_licencia' => 'Dias licencia',
            'afecta_transporte' => 'Afecta transporte',
            'pagar_empleado' => 'Pagar Empleado',
            'pagar_arl' => 'Pagar Arl',
            'pagar_parafiscal' => 'Pagar parafiscal',
            'cobrar_administradora' => 'Cobrar Administradora',
            'aplicar_adicional'=> 'Aplicar adicional',
            'observacion' => 'Observacion',
        ];
    }
    
}
