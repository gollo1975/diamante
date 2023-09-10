<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;

class Users extends \yii\db\ActiveRecord{

    public static function getDb()
    {
        return Yii::$app->db;
    }

    public static function tableName()
    {
        return 'usuario';
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codusuario' => 'Id',
            'username' => 'Usuario',
            'role' => 'Perfil',
            'documentousuario' => 'Identificación',
            'nombrecompleto' => 'Nombre Completo',
            'emailusuario' => 'Email',
            'activo' => 'Estado',
            'fechaproceso' => 'Fecha Creación',            
        ];
    }
    
    public function getPerfil()
    {
        if($this->role == 1){
            $perfil = "USUARIO";
        }else{
            if($this->role == 2){
               $perfil = "ADMINISTRADOR";
            }else{
               $perfil = "VENDEDOR"; 
            }   
        }
        return $perfil;
    }
    
    public function getEstado()
    {
        if($this->activo == 1){
            $estado = "Activo";
        }else{
            $estado = "Desactivo";
        }
        return $estado;
    }

}