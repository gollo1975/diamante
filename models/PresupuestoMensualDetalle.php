<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presupuesto_mensual_detalle".
 *
 * @property int $id_detalle
 * @property int $id_mensual
 * @property int $id_cliente
 * @property int $gasto_mensual
 * @property int $presupuesto_asignado
 * @property string $fecha_hora
 *
 * @property PresupuestoMensual $mensual
 * @property Clientes $cliente
 */
class PresupuestoMensualDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presupuesto_mensual_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_mensual', 'id_cliente', 'gasto_mensual', 'presupuesto_asignado'], 'integer'],
            [['fecha_hora'], 'safe'],
            [['id_mensual'], 'exist', 'skipOnError' => true, 'targetClass' => PresupuestoMensual::className(), 'targetAttribute' => ['id_mensual' => 'id_mensual']],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_mensual' => 'Id Mensual',
            'id_cliente' => 'Id Cliente',
            'gasto_mensual' => 'Gasto Mensual',
            'presupuesto_asignado' => 'Presupuesto Asignado',
            'fecha_hora' => 'Fecha Hora',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMensual()
    {
        return $this->hasOne(PresupuestoMensual::className(), ['id_mensual' => 'id_mensual']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }
}
