<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class FormModeloCrearCita extends Model
{
    public $desde;
    public $hasta;
    public $anocierre;
    public $vendedor;

    public function rules()
    {
        return [

           [['desde','hasta'], 'safe'],
           [['anocierre','vendedor'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'desde' => 'Desde:',
            'hasta' => 'Hasta:',
            'anocierre' => 'Año',
            'vendedor' => 'Vendedor:',

        ];
    }
}
