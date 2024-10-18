<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "refinanciar_credito".
 *
 * @property int $id_financiacion
 * @property int $id_credito
 * @property int $id_empleado
 * @property int $adicionar_valor
 * @property int $nuevo_saldo
 * @property int $numero_cuotas
 * @property int $numero_cuota_actual
 * @property int $valor_cuota
 * @property string $nota
 * @property string $user_name
 * @property string $fecha_proceso
 * @property string $fecha_hora_registro
 *
 * @property Credito $credito
 * @property Empleados $empleado
 */
class RefinanciarCredito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refinanciar_credito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_credito', 'id_empleado', 'adicionar_valor', 'nuevo_saldo', 'numero_cuotas', 'numero_cuota_actual', 'valor_cuota'], 'integer'],
            [['adicionar_valor', 'numero_cuotas', 'numero_cuota_actual'], 'required'],
            [['fecha_proceso', 'fecha_hora_registro'], 'safe'],
            [['nota'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 15],
            [['id_credito'], 'exist', 'skipOnError' => true, 'targetClass' => Credito::className(), 'targetAttribute' => ['id_credito' => 'id_credito']],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleados::className(), 'targetAttribute' => ['id_empleado' => 'id_empleado']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_financiacion' => 'Id Financiacion',
            'id_credito' => 'Id Credito',
            'id_empleado' => 'Id Empleado',
            'adicionar_valor' => 'Adicionar Valor',
            'nuevo_saldo' => 'Nuevo Saldo',
            'numero_cuotas' => 'Numero Cuotas',
            'numero_cuota_actual' => 'Numero Cuota Actual',
            'valor_cuota' => 'Valor Cuota',
            'nota' => 'Nota',
            'user_name' => 'User Name',
            'fecha_proceso' => 'Fecha Proceso',
            'fecha_hora_registro' => 'Fecha Hora Registro',
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
    public function getEmpleado()
    {
        return $this->hasOne(Empleados::className(), ['id_empleado' => 'id_empleado']);
    }
}
