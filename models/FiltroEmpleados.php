<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FiltroEmpleados extends Model
{        
   
    public $documento;
    public $empleado;
    public $tipo_empleado;
    public $desde;
    public $hasta;
    public $estado;
    
    public function rules()
    {
        return [  
          
            [['documento','estado','tipo_empleado'], 'integer'],
            [['empleado'], 'string'],
            [['desde','hasta'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'documento' => 'Documento:',
            'empleado' => 'Nombre del empleado:',
            'estado' => 'Activo:',
            'tipo_empleado' => 'Area operativa',
            'desde' => 'Fecha inicio:',
            'hasta' => 'Fecha corte:',

        ];
    }
    
}
