<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "almacen".
 *
 * @property int $id_almacen
 * @property string $almacen
 * @property string $fecha_registro
 * @property string $user_name
 *
 * @property OrdenProduccion[] $ordenProduccions
 */
class Almacen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'almacen';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->almacen = strtoupper($this->almacen); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['almacen'], 'required'],
            [['fecha_registro'], 'safe'],
            [['almacen'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_almacen' => 'Código',
            'almacen' => 'Almacen/Bodega',
            'fecha_registro' => 'Fecha registro',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenProduccions()
    {
        return $this->hasMany(OrdenProduccion::className(), ['id_almacen' => 'id_almacen']);
    }
}
