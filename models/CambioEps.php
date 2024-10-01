<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cambio_eps".
 *
 * @property int $id_cambio
 * @property int $id_contrato
 * @property int $id_entidad_salud_anterior
 * @property int $id_entidad_salud_nueva
 * @property string $fecha_cambio
 * @property string $user_name
 * @property string $observacion
 * @property string $fecha_hora_registro
 *
 * @property Contratos $contrato
 * @property EntidadSalud $entidadSaludAnterior
 * @property EntidadSalud $entidadSaludNueva
 */
class CambioEps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cambio_eps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entidad_salud_nueva'], 'required'],
            [['id_contrato', 'id_entidad_salud_anterior', 'id_entidad_salud_nueva'], 'integer'],
            [['fecha_cambio', 'fecha_hora_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 50],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_entidad_salud_anterior'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadSalud::className(), 'targetAttribute' => ['id_entidad_salud_anterior' => 'id_entidad_salud']],
            [['id_entidad_salud_nueva'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadSalud::className(), 'targetAttribute' => ['id_entidad_salud_nueva' => 'id_entidad_salud']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cambio' => 'Codigo',
            'id_contrato' => 'Numero de contrato:',
            'id_entidad_salud_anterior' => 'Eps anterior:',
            'id_entidad_salud_nueva' => 'Nueva eps:',
            'fecha_cambio' => 'Fecha cambio',
            'user_name' => 'User Name',
            'observacion' => 'Observacion',
            'fecha_hora_registro' => 'Fecha Hora Registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contratos::className(), ['id_contrato' => 'id_contrato']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidadSaludAnterior()
    {
        return $this->hasOne(EntidadSalud::className(), ['id_entidad_salud' => 'id_entidad_salud_anterior']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidadSaludNueva()
    {
        return $this->hasOne(EntidadSalud::className(), ['id_entidad_salud' => 'id_entidad_salud_nueva']);
    }
}
