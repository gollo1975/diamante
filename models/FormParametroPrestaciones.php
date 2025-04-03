<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\PrestacionesSocialesDetalle;


/**
 * ContactForm is the model behind the contact form.
 */
class FormParametroPrestaciones extends Model
{        
    public $id;
    public $nro_dias;    
    public $dias_ausentes;
    public $salario_promedio_prima;
    public $total_dias;
    public $valor_pagar;
    public $auxilio_transporte;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nro_dias','dias_ausentes','salario_promedio_prima','total_dias','auxilio_transporte'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [            
            'id' => 'Id',
            'nro_dias' => 'Nro Dias:',
            'dias_ausentes' => 'Dias ausente:',            
            'salario_promedio_prima' => 'Salario promedio:',
            'auxilio_transporte' => 'A_Transporte:',
        ];
    }
}
