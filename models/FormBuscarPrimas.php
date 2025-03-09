<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormBuscarPrimas extends Model
{
    public $documento;
    public $id_grupo_pago;   

    public function rules()
    {
        return [

            ['documento', 'default' ],
            ['id_grupo_pago', 'default'], 
        ];
    }

    public function attributeLabels()
    {
        return [

            'documento' => 'Documento:',
            'id_grupo_pago' => 'Grupo pago:',    
        ];
    }
}
