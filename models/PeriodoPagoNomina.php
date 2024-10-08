<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "periodo_pago_nomina".
 *
 * @property int $id_periodo_pago_nomina
 * @property int $id_grupo_pago
 * @property int $id_periodo_pago
 * @property int $id_tipo_nomina
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property string $fecha_real_corte
 * @property string $fecha_creacion
 * @property int $dias_periodo
 * @property int $estado_periodo
 * @property int $cantidad_empleado
 * @property string $user_name
 *
 * @property GrupoPago $grupoPago
 * @property PeriodoPago $periodoPago
 * @property TipoNomina $tipoNomina
 */
class PeriodoPagoNomina extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodo_pago_nomina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_grupo_pago', 'id_periodo_pago', 'id_tipo_nomina', 'fecha_desde', 'fecha_hasta'], 'required'],
            [['id_grupo_pago', 'id_periodo_pago', 'id_tipo_nomina', 'dias_periodo', 'estado_periodo', 'cantidad_empleado'], 'integer'],
            [['fecha_desde', 'fecha_hasta', 'fecha_real_corte', 'fecha_creacion'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_grupo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoPago::className(), 'targetAttribute' => ['id_grupo_pago' => 'id_grupo_pago']],
            [['id_periodo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoPago::className(), 'targetAttribute' => ['id_periodo_pago' => 'id_periodo_pago']],
            [['id_tipo_nomina'], 'exist', 'skipOnError' => true, 'targetClass' => TipoNomina::className(), 'targetAttribute' => ['id_tipo_nomina' => 'id_tipo_nomina']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_periodo_pago_nomina' => 'Id',
            'id_grupo_pago' => 'Grupo de pago:',
            'id_periodo_pago' => 'Periodo pago:',
            'id_tipo_nomina' => 'Tipo de pago:',
            'fecha_desde' => 'Fecha Desde',
            'fecha_hasta' => 'Fecha Hasta',
            'fecha_real_corte' => 'Fecha Real Corte',
            'fecha_creacion' => 'Fecha Creacion',
            'dias_periodo' => 'Dias Periodo',
            'estado_periodo' => 'Activo',
            'cantidad_empleado' => 'Cantidad Empleado',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoPago()
    {
        return $this->hasOne(GrupoPago::className(), ['id_grupo_pago' => 'id_grupo_pago']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoPago()
    {
        return $this->hasOne(PeriodoPago::className(), ['id_periodo_pago' => 'id_periodo_pago']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoNomina()
    {
        return $this->hasOne(TipoNomina::className(), ['id_tipo_nomina' => 'id_tipo_nomina']);
    }
}
