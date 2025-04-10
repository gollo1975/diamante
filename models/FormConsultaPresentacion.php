<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormConsultaPresentacion extends Model
{
 
    public $presentacion;
    public $producto;
    public $grupo;
    public $orden;



    public function rules()
    {
        return [

            [['producto','grupo'], 'integer'],
            [['presentacion','orden'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'producto' => 'Nombre del producto:',
            'grupo' => 'Nombre del grupo:',
            'presentacion' =>'Presentacion producto:',
            'orden' => 'Ordenamiento:'
         
        ];
    }
}