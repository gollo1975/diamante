<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaciones".
 *
 * @property int $tipo_transacion
 * @property string $descripcion
 * @property int $codigo_enlace
 *
 * @property Empleados[] $empleados
 */
class Transaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['codigo_enlace'], 'integer'],
            [['descripcion'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tipo_transacion' => 'Tipo Transacion',
            'descripcion' => 'Descripcion',
            'codigo_enlace' => 'Codigo Enlace',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleados()
    {
        return $this->hasMany(Empleados::className(), ['tipo_transacion' => 'tipo_transacion']);
    }
}
