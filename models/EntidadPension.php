<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidad_pension".
 *
 * @property int $id_entidad_pension
 * @property string $entidad
 * @property int $estado
 */
class EntidadPension extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidad_pension';
    }
    
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->entidad = strtoupper($this->entidad); 
        $this->codigo_interfaz = strtoupper($this->codigo_interfaz); 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entidad_pension', 'entidad'], 'required'],
            [['id_entidad_pension', 'estado'], 'integer'],
            [['entidad','codigo_interfaz'], 'string', 'max' => 40],
             [['codigo_interfaz'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entidad_pension' => 'Codigo',
            'entidad' => 'Entidad de pension',
            'estado' => 'Estado',
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
