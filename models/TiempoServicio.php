<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tiempo_servicio".
 *
 * @property int $id_tiempo
 * @property string $tiempo_servicio
 * @property double $horas_dia
 * @property double $pago_incapacidad_general
 * @property double $pago_incapacidad_laboral
 * @property string $user_name
 */
class TiempoServicio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tiempo_servicio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tiempo_servicio', 'horas_dia'], 'required'],
            [['horas_dia', 'pago_incapacidad_general', 'pago_incapacidad_laboral'], 'number'],
            [['tiempo_servicio'], 'string', 'max' => 90],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tiempo' => 'Id Tiempo',
            'tiempo_servicio' => 'Tiempo Servicio',
            'horas_dia' => 'Horas Dia',
            'pago_incapacidad_general' => 'Pago Incapacidad General',
            'pago_incapacidad_laboral' => 'Pago Incapacidad Laboral',
            'user_name' => 'User Name',
        ];
    }
}
