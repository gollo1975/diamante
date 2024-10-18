<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormAbonoCredito extends Model
{        
   
    public $id_credito;
    public $observacion;
    public $id_tipo_pago;
    public $valor_abono;
    public $saldo_credito;
    public $fecha_creacion;
    public $fecha_abono;

    public function rules()
    {
        return [  
           [['valor_abono','id_tipo_pago','fecha_abono'], 'required'] ,
           [['id_credito', 'id_tipo_pago'], 'integer'],
           [['valor_abono'],'number'],
            [['fecha_creacion','fecha_abono'], 'safe'],
           [['observacion'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [   
            'id_credito' => 'id_credito:',
            'observacion' => 'Observación:',
            'id_tipo_pago'=>'Tipo pago:',
            'valor_abono'=>'Valor abono:',
            'saldo_credito' =>'saldo_credito',
            'fecha_abono' => 'Fecha de abono',
       
        ];
    }
    
}
