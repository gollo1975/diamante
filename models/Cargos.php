<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cargos".
 *
 * @property int $id_cargo
 * @property string $nombre_cargo
 * @property string $fecha_registro
 * @property string $user_name
 */
class Cargos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cargos';
    }
    
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombre_cargo = strtoupper($this->nombre_cargo); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_cargo'], 'required'],
            [['fecha_registro'], 'safe'],
            [['nombre_cargo'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cargo' => 'Código',
            'nombre_cargo' => 'Nombre cargo',
            'fecha_registro' => 'Fecha registro',
            'user_name' => 'User Name',
        ];
    }
}
