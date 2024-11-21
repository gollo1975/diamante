<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormModeloPackin extends Model
{
    public $cantidad_caja;   
    public $unidades_porcaja;
        
    public function rules()
    {
        return [
            [['cantidad_caja','unidades_porcaja'], 'required'],
            [['cantidad_caja','unidades_porcaja'], 'integer'],
            
        ];
    }

    public function attributeLabels()
    {
        return [
            'cantidad_caja' => 'Numero  de cajas:',  
            'unidades_porcaja' =>'Cantidad x caja:',
        ];
    }
}
