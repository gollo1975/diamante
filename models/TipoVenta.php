<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_venta".
 *
 * @property int $id_tipo_venta
 * @property string $concepto
 */
class TipoVenta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_venta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['concepto'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_venta' => 'Id Tipo Venta',
            'concepto' => 'Concepto',
        ];
    }
}
