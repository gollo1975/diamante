<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_pension".
 *
 * @property int $id_configuracion_pension
 * @property string $concepto
 * @property double $porcentaje_empleado
 * @property double $porcentaje_empleador
 *
 * @property Contratos[] $contratos
 */
class ConfiguracionPension extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_pension';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto', 'porcentaje_empleado', 'porcentaje_empleador'], 'required'],
            [['porcentaje_empleado', 'porcentaje_empleador'], 'number'],
            [['concepto'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_configuracion_pension' => 'Id Configuracion Pension',
            'concepto' => 'Concepto',
            'porcentaje_empleado' => 'Porcentaje Empleado',
            'porcentaje_empleador' => 'Porcentaje Empleador',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContratos()
    {
        return $this->hasMany(Contratos::className(), ['id_configuracion_pension' => 'id_configuracion_pension']);
    }
}
