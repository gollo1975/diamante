<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroPagoFecha extends Model
{
    public $estado_proceso;
    public $fecha_corte;


    public function rules()
    {
        return [

            [['estado_proceso'], 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            [['fecha_corte'], 'safe'],
           
           
        ];
    }

    public function attributeLabels()
    {
        return [
            'estado_proceso' => 'Abiero/Cerrado:',
            'fecha_corte' => 'Fecha de corte:',
           
           
        ];
    }
}