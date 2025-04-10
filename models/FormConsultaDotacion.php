<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormConsultaDotacion extends Model
{
 
    public $numero;
    public $empleado;
    public $desde;
    public $hasta;
    public $tipo_dotacion;



    public function rules()
    {
        return [

            [['numero','empleado','tipo_dotacion'], 'integer'],
            [['desde','hasta'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'numero' => 'Numero de entrega:',
            'empleado' => 'Nombre empleado:',
            'desde' =>'Desde:',
            'hasta' => 'Hasta:',
            'tipo_dotacion' => 'Tipo de dotacion:'
         
        ];
    }
}