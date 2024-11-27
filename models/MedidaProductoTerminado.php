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
        $this->codigo_enlace = strtoupper($this->codigo_enlace); 
 
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion','codigo_enlace'], 'required'],
            [['descripcion'], 'string', 'max' => 15],
            [['codigo_enlace','unidad_medida'], 'string', 'max' => 3],
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
            'codigo_enlace' => 'Unida de medida',
            'unidad_medida' => 'Unidad medida',
        ];
    }
}
