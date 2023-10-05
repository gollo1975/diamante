<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_factura_venta".
 *
 * @property int $id_tipo_factura
 * @property string $descripcion
 * @property string $user_name
 * @property string $fecha_registro
 */
class TipoFacturaVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_factura_venta';
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
            [['fecha_registro'], 'safe'],
            [['descripcion'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
            [['porcentaje_retencion'], 'number'],
            [['base_retencion'], 'integer'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_factura' => 'Codigo',
            'descripcion' => 'Tipo documento',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha registro',
            'porcentaje_retencion' => '% Retencion',
            'base_retencion' => 'Base retencion',
        ];
    }
}
