<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormMaquinaBuscar extends Model
{
    public $q;    
    public $grupo;
    public $nombres;

    public function rules()
    {
        return [

            ['q', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan números y letras'],   
            [['grupo'],'integer'],
            [['nombres'],'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'q' => 'Dato a Buscar:',   
            'grupo' => 'Grupo de pago:',
            'nombres' => 'Seleccione el empleado:',
        ];
    }
}
