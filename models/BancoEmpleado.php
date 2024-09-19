<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banco_empleado".
 *
 * @property int $id_banco
 * @property string $entidad
 * @property string $codigo_interfaz
 */
class BancoEmpleado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banco_empleado';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->entidad = strtoupper($this->entidad);
        $this->codigo_interfaz= strtoupper($this->codigo_interfaz);
        return true;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entidad'], 'required'],
            [['entidad'], 'string', 'max' => 30],
            [['codigo_interfaz'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_banco' => 'Id Banco',
            'entidad' => 'Entidad',
            'codigo_interfaz' => 'Codigo Interfaz',
        ];
    }
}
