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
            [['abreviatura'], 'string', 'max' => 1],
            [['codigo_interface','aplica_descuento_comercial','dias_descuento_uno','dias_descuento_dos'], 'integer'],
            [['porcentaje_descuento_uno','porcentaje_descuento_dos'], 'number'],
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
            'codigo_interface' => 'codigo_interface',
            'abreviatura' => 'abreviatura',
            'aplica_descuento_comercial' => 'aplica_descuento_comercial',
            'dias_descuento_dos' => 'dias_descuento_dos',
            'dias_descuento_uno' => 'dias_descuento_uno',
            'porcentaje_descuento_uno' => 'porcentaje_descuento_uno',
            'porcentaje_descuento_dos' => 'porcentaje_descuento_dos',
            
        ];
    }
}
