<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//ESTE PROCESO SIRVE PARA EL CUPO AL CLIENTE Y EL NUEVO PRECIO DE VENTA PARA INVENTARIO DIRECTO
class ModeloParametroCliente extends Model
{
    public $activo;
    public $presupuesto;
    public $aplicar_venta_mora;

    public function rules()
    {
        return [

           [['activo','presupuesto','aplicar_venta_mora'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'activo' => 'Activo:',
            'presupuesto' => 'Presupuesto:',
            'aplicar_venta_mora' => 'Venta en mora:',

        ];
    }
}
