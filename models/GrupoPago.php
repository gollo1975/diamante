<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grupo_pago".
 *
 * @property int $id_grupo_pago
 * @property string $grupo_pago
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property int $id_sucursal
 * @property string $ultimo_pago_nomina
 * @property string $ultimo_pago_prima
 * @property string $ultimo_pago_cesantia
 * @property int $limite_devengado
 * @property int $dias_pago
 * @property int $estado
 * @property string $observacion
 * @property string $user_name
 * @property string $fecha_hora_registro
 */
class GrupoPago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grupo_pago';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo_pago', 'codigo_departamento', 'codigo_municipio', 'id_sucursal', 'ultimo_pago_nomina', 'ultimo_pago_prima', 'ultimo_pago_cesantia', 'dias_pago'], 'required'],
            [['id_sucursal', 'limite_devengado', 'dias_pago', 'estado'], 'integer'],
            [['ultimo_pago_nomina', 'ultimo_pago_prima', 'ultimo_pago_cesantia', 'fecha_hora_registro'], 'safe'],
            [['grupo_pago'], 'string', 'max' => 40],
            [['codigo_departamento', 'codigo_municipio'], 'string', 'max' => 10],
            [['observacion'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_grupo_pago' => 'Id Grupo Pago',
            'grupo_pago' => 'Grupo Pago',
            'codigo_departamento' => 'Codigo Departamento',
            'codigo_municipio' => 'Codigo Municipio',
            'id_sucursal' => 'Id Sucursal',
            'ultimo_pago_nomina' => 'Ultimo Pago Nomina',
            'ultimo_pago_prima' => 'Ultimo Pago Prima',
            'ultimo_pago_cesantia' => 'Ultimo Pago Cesantia',
            'limite_devengado' => 'Limite Devengado',
            'dias_pago' => 'Dias Pago',
            'estado' => 'Estado',
            'observacion' => 'Observacion',
            'user_name' => 'User Name',
            'fecha_hora_registro' => 'Fecha Hora Registro',
        ];
    }
}
