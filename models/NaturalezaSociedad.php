<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "naturaleza_sociedad".
 *
 * @property int $id_naturaleza
 * @property string $naturaleza
 * @property string $codigo_interfaz
 *
 * @property Proveedor[] $proveedors
 */
class NaturalezaSociedad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'naturaleza_sociedad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['naturaleza'], 'required'],
            [['naturaleza'], 'string', 'max' => 30],
            [['codigo_interfaz'], 'string', 'max' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_naturaleza' => 'Id Naturaleza',
            'naturaleza' => 'Naturaleza',
            'codigo_interfaz' => 'Codigo Interfaz',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProveedors()
    {
        return $this->hasMany(Proveedor::className(), ['id_naturaleza' => 'id_naturaleza']);
    }
}
