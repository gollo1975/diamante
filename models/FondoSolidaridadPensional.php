<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fondo_solidaridad_pensional".
 *
 * @property int $id_fsp
 * @property int $rango1
 * @property int $rango2
 * @property double $porcentaje
 * @property string $detalle
 */
class FondoSolidaridadPensional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fondo_solidaridad_pensional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rango1', 'rango2'], 'integer'],
            [['porcentaje_solidaridad','porcentaje_subcuenta','porcentaje'], 'number'],
            [['detalle'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_fsp' => 'Id Fsp',
            'rango1' => 'Rango1',
            'rango2' => 'Rango2',
            'porcentaje_subcuenta' => 'porcentaje_subcuenta',
            'porcentaje_solidaridad' => 'porcentaje_solidaridad',
            'porcentaje' => 'porcentaje',
            'detalle' => 'Detalle',
        ];
    }
}
