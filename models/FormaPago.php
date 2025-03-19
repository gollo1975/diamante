<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "forma_pago".
 *
 * @property int $id_forma_pago
 * @property string $concepto
 * @property string $fecha_registro
 *
 * @property ReciboCajaPuntoVenta[] $reciboCajaPuntoVentas
 */
class FormaPago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forma_pago';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['fecha_registro'], 'safe'],
            [['concepto'], 'string', 'max' => 30],
            [['abreviatura'], 'string', 'max' => 2],
            [['codigo_api','servicio_nomina','servicio_proveedor','codigo_api_ds','codigo_api_nomina'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_forma_pago' => 'Id Forma Pago',
            'concepto' => 'Concepto',
            'fecha_registro' => 'Fecha Registro',
            'abreviatura' => 'abreviatura',
            'codigo_api' => 'codigo_api',
            'servicio_nomina' => 'servicio_nomina',
            'servicio_proveedor' => 'servicio_proveedor',
            'codigo_api' => 'codigo_api',
            'codigo_api_ds' => 'codigo_api_ds',
            'codigo_api_nomina' => 'codigo_api_nomina',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReciboCajaPuntoVentas()
    {
        return $this->hasMany(ReciboCajaPuntoVenta::className(), ['id_forma_pago' => 'id_forma_pago']);
    }
}
