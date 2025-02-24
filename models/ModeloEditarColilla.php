<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ModeloEditarColilla extends Model
{
    public $deduccion;   
    public $devengado;
        
    public function rules()
    {
        return [
            [['deduccion','devengado'], 'integer'],
            
        ];
    }

    public function attributeLabels()
    {
        return [
            'deduccion' => 'Valor deduccion:',  
            'devengado' =>'Valor devengado:',
        ];
    }
}
