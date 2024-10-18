<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormAdicionPermanente extends Model
{        
    public $id_pago_permanente;
    public $id_empleado;
    public $codigo_salario;            
    public $valor_adicion;    
    public $aplicar_dia_laborado;
    public $aplicar_prima;
    public $aplicar_cesantias;    
    public $detalle;





    public function rules()
    {
        return [            
            [['id_empleado','codigo_salario','valor_adicion'], 'required'],
            [['valor_adicion','aplicar_dia_laborado','aplicar_prima','aplicar_cesantias'], 'integer'],
            [['detalle'],'string', 'max'=>50],
        ];
    }

    public function attributeLabels()
    {
        return [                                                       
            'id_empleado' => 'Empleado:',
            'codigo_salario' => 'Concepto de salario:',
            'valor_adicion' => 'Valor adicion:',                        
            'aplicar_dia_laborado' => 'Dia laborado',
            'aplicar_prima'=>'Prima semestral',
            'aplicar_cesantias'=>'Cesantias',            
            'detalle'=>'Detalle:',    
            
            
        ];
    }
    
}
