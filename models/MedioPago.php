<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "medio_pago".
 *
 * @property int $id_medio_pago
 * @property string $concepto
 * @property string $codigo_interface
 */
class MedioPago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medio_pago';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto', 'codigo_interface'], 'required'],
            [['concepto'], 'string', 'max' => 30],
            [['codigo_interface'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_medio_pago' => 'Id Medio Pago',
            'concepto' => 'Concepto',
            'codigo_interface' => 'Codigo Interface',
        ];
    }
}
