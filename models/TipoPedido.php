<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_pedido".
 *
 * @property int $tipo_pedido
 * @property string $concepto
 * @property int $codigo_interface
 */
class TipoPedido extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_pedido';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto', 'codigo_interface'], 'required'],
            [['codigo_interface'], 'string'],
            [['concepto'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tipo_pedido' => 'Tipo Pedido',
            'concepto' => 'Concepto',
            'codigo_interface' => 'Codigo Interface',
        ];
    }
}
