<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_cliente".
 *
 * @property int $id_tipo_cliente
 * @property string $concepto
 */
class TipoCliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_cliente';
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
            'id_tipo_cliente' => 'Id Tipo Cliente',
            'concepto' => 'Concepto',
            
        ];
    }
}
