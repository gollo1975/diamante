<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroLicencia extends Model
{        
    public $codigo_licencia;
    public $id_empleado;
    public $identificacion;
    public $id_grupo_pago;
    public $fecha_desde ;
    public $fecha_hasta;
    public $dias_licencia;


    public function rules()
    {
        return [            
            [['codigo_licencia','id_empleado','identificacion', 'id_grupo_pago'], 'integer'],
            [['fecha_desde','fecha_hasta'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [                        
            'id_grupo_pago' => 'Grupo Pago:',                      
            'id_empleado' => 'Empleado:',
            'identificacion'=>'Documento',
            'fecha_desde' => 'Desde:',
            'fecha_hasta' => 'Hasta:',
            'dias_licencia' => 'Dias licencia:',
            'codigo_licencia' => 'Tipo licencia:',
        ];
    }
    
}
