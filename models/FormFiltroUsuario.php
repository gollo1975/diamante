<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroUsuario extends Model
{
    public $nombreusuario;
    public $documentousuario;
    public $nombrecompleto;

    public function rules()
    {
        return [

            ['nombreusuario', 'default'],
            ['documentousuario', 'default'],
            ['nombrecompleto', 'default'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nombreusuario' => 'Usuario',
            'documentousuario' => 'Identificación:',
            'nombrecompleto' => 'Nombre Completo:',
        ];
    }
}