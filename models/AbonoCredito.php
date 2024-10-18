<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "abono_credito".
 *
 * @property int $id_abono
 * @property int $id_credito
 * @property int $id_tipo_pago
 * @property double $valor_abono
 * @property double $saldo
 * @property int $cuota_pendiente
 * @property string $observacion
 * @property string $fecha_proceso
 * @property string $user_name
 *
 * @property Credito $credito
 * @property TipoPagoCredito $tipoPago
 */
class AbonoCredito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'abono_credito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_credito', 'id_tipo_pago', 'cuota_pendiente'], 'integer'],
            [['valor_abono', 'saldo'], 'number'],
            [['fecha_proceso','fecha_abono'], 'safe'],
            [['observacion'], 'string', 'max' => 50],
            [['user_name'], 'string', 'max' => 30],
            [['id_credito'], 'exist', 'skipOnError' => true, 'targetClass' => Credito::className(), 'targetAttribute' => ['id_credito' => 'id_credito']],
            [['id_tipo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => TipoPagoCredito::className(), 'targetAttribute' => ['id_tipo_pago' => 'id_tipo_pago']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_abono' => 'Id Abono',
            'id_credito' => 'Id Credito',
            'id_tipo_pago' => 'Id Tipo Pago',
            'valor_abono' => 'Valor Abono',
            'saldo' => 'Saldo',
            'cuota_pendiente' => 'Cuota Pendiente',
            'observacion' => 'Observacion',
            'fecha_proceso' => 'Fecha Proceso',
            'user_name' => 'User Name',
            'fecha_abono' => 'Fecha de abono:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredito()
    {
        return $this->hasOne(Credito::className(), ['id_credito' => 'id_credito']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoPago()
    {
        return $this->hasOne(TipoPagoCredito::className(), ['id_tipo_pago' => 'id_tipo_pago']);
    }
}
