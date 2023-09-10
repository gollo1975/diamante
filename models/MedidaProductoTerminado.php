<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "medida_producto_terminado".
 *
 * @property int $id_medida_producto
 * @property string $descripcion
 */
class MedidaProductoTerminado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medida_producto_terminado';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->descripcion = strtoupper($this->descripcion); 
 
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_medida_producto' => 'Código',
            'descripcion' => 'Descripcion',
        ];
    }
}
