<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroContratos extends Model
{        
   
    public $empleado;
    public $tipo_contrato;
    public $desde;
    public $hasta;
    public $estado;
    public $grupo_pago;
    public $eps;
    public $pension;


    public function rules()
    {
        return [  
          
            [['empleado','estado','tipo_contrato','grupo_pago','eps','pension'], 'integer'],
            [['desde','hasta'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'tipo_contrato' => 'Tipo de contrato:',
            'empleado' => 'Nombre del empleado:',
            'estado' => 'Activo:',
            'grupo_pago' => 'Grupo de pago:',
            'desde' => 'Fecha inicio:',
            'hasta' => 'Fecha corte:',
            'eps' => 'Entidad de salud:',
            'pension' => 'Entidad de pensión:'

        ];
    }
    
}
