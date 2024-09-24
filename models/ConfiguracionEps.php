<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_eps".
 *
 * @property int $id_configuracion_eps
 * @property string $concepto_eps
 * @property double $porcentaje_empleado_eps
 * @property double $porcentaje_empleador_eps
 *
 * @property Contratos[] $contratos
 */
class ConfiguracionEps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_eps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto_eps', 'porcentaje_empleado_eps', 'porcentaje_empleador_eps'], 'required'],
            [['porcentaje_empleado_eps', 'porcentaje_empleador_eps'], 'number'],
            [['concepto_eps'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_configuracion_eps' => 'Id Configuracion Eps',
            'concepto_eps' => 'Concepto Eps',
            'porcentaje_empleado_eps' => 'Porcentaje Empleado Eps',
            'porcentaje_empleador_eps' => 'Porcentaje Empleador Eps',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContratos()
    {
        return $this->hasMany(Contratos::className(), ['id_configuracion_eps' => 'id_configuracion_eps']);
    }
}
