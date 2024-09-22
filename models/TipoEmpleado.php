<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_empleado".
 *
 * @property int $tipo_empleado
 * @property string $decripcion
 *
 * @property Empleados[] $empleados
 */
class TipoEmpleado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_empleado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tipo_empleado' => 'Tipo Empleado',
            'descripcion' => 'Decripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleados()
    {
        return $this->hasMany(Empleados::className(), ['tipo_empleado' => 'tipo_empleado']);
    }
}
