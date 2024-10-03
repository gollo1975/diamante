<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroIncapacidad extends Model
{        
    public $id_grupo_pago;
    public $id_empleado;
    public $id_contrato;
    public $id_diagnostico;
    public $fecha_inicio ;
    public $fecha_final;
    public $dias_incapacidad;
    public $codigo_incapacidad;
    public $numero_incapacidad;


    public function rules()
    {
        return [            
            [['id_grupo_pago','id_empleado','id_contrato','id_diagnostico', 'dias_incapacidad', 'codigo_incapacidad', 'numero_incapacidad'], 'integer'],
            [['fecha_inicio','fecha_final'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [                        
            'id_grupo_pago' => 'Grupo Pago:',                      
            'id_empleado' => 'Empleado:',
            'id_contrato' => 'Codigo contrato:',
            'id_diagnostico' => 'Diagnóstico:',
            'fecha_inicio' => 'Desde:',
            'fecha_final' => 'Hasta:',
            'numero_incapacidad'=>'Numero incapacidad',
            'dias_incapacidad' => 'Dias incapacitado:',
            'codigo_incapacidad' => 'Tipo incapacidad:',
        ];
    }
    
}
