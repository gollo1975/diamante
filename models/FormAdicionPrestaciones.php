<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormAdicionPrestaciones extends Model
{        
    public $id_adicion;
    public $id_prestacion;
    public $codigo_salario;            
    public $valor_adicion;    
    public $observacion;
    public $tipo_adicion;









    public function rules()
    {
        return [            
            [['codigo_salario','valor_adicion'], 'required'],
            [['valor_adicion','codigo_salario','tipo_adicion'], 'integer'],
            [['observacion'],'string', 'max'=>100],
        ];
    }

    public function attributeLabels()
    {
        return [                                                       
            'codigo_salario' => 'Concepto salario:',
            'valor_adicion' => 'Valor:',                        
            'observacion'=>'Observacion:',    
           
        ];
    }
    
}
