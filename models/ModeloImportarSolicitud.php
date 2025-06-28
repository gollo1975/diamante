<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ModeloImportarSolicitud extends Model
{
    public $cantidad_entregada;   
    public $tipo_entrega;
    public $tipo_solicitud;


    public function rules()
    {
        return [
            [['cantidad_entregada','tipo_entrega','tipo_solicitud'], 'integer'],
            ['cantidad_entregada', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            
        ];
    }

    public function attributeLabels()
    {
        return [
            'cantidad_entregada' => 'Cantidad a entregar:',  
            'tipo_entrega' =>'Tipo de entrega:',
            'tipo_solicitud' =>  'Tipo de solicitud:',
            
        ];
    }
}
