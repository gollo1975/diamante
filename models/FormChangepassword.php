<?php

namespace app\models;
use Yii;
use yii\base\model;
use app\models\Users;

class FormChangepassword extends model{

    public $codusuario;    
    public $password;
    public $authKey;
    public $accessToken;    
    public $password_repeat;

    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required', 'message' => 'Campo requerido'],            
            ['password', 'match', 'pattern' => "/^.{8,16}$/", 'message' => 'Mínimo 8 y máximo 16 caracteres'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Los passwords no coinciden'],
        ];
    }

    public function attributeLabels()
    {
        return [            
            'password' => 'Nueva Clave:',
            'password_repeat' => 'Confirmar Clave:',
        ];
    }    

}