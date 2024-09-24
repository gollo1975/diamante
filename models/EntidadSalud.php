<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidad_salud".
 *
 * @property int $id_entidad_salud
 * @property string $entidad_salud
 * @property int $estado
 * @property string $user_name
 */
class EntidadSalud extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidad_salud';
    }
    
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->entidad_salud = strtoupper($this->entidad_salud); 
        $this->codigo_interfaz = strtoupper($this->codigo_interfaz); 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entidad_salud'], 'required'],
            [['estado'], 'integer'],
            [['entidad_salud'], 'string', 'max' => 40],
            [['user_name'], 'string', 'max' => 15],
            [['codigo_interfaz'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entidad_salud' => 'Codigo',
            'entidad_salud' => 'Entidad de salud',
            'estado' => 'Activo',
            'user_name' => 'User Name',
            'codigo_interfaz' => 'Codigo interfaz',
        ];
    }
    
    public function getActivo() {
        if($this->estado == 0){
            $activo = 'SI';
        }else{
            $activo = 'NO';
        }
        return $activo;
    }
}
