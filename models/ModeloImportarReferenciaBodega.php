<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ModeloImportarReferenciaBodega extends Model
{
 
    public $unidades;
  



    public function rules()
    {
        return [
           [['unidades'], 'integer'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
           
           
            'unidades' => 'Cantidad:',
            
        ];
    }
}
