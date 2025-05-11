<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cambio_pension".
 *
 * @property int $id_cambio
 * @property int $id_contrato
 * @property int $id_entidad_pension_anterior
 * @property int $id_entidad_pension_nueva
 * @property string $fecha_cambio
 * @property string $user_name
 * @property string $observacion
 * @property string $fecha_hora_registro
 *
 * @property Contratos $contrato
 * @property EntidadPension $entidadPensionAnterior
 * @property EntidadPension $entidadPensionNueva
 */
class CambioPension extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cambio_pension';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_contrato', 'id_entidad_pension_anterior', 'id_entidad_pension_nueva'], 'integer'],
            [['id_entidad_pension_nueva'], 'required'],
            [['fecha_cambio', 'fecha_hora_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 50],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_entidad_pension_anterior'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadPension::className(), 'targetAttribute' => ['id_entidad_pension_anterior' => 'id_entidad_pension']],
            [['id_entidad_pension_nueva'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadPension::className(), 'targetAttribute' => ['id_entidad_pension_nueva' => 'id_entidad_pension']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cambio' => 'Id Cambio',
            'id_contrato' => 'Contrato',
            'id_entidad_pension_anterior' => 'Entidad Pension Anterior',
            'id_entidad_pension_nueva' => 'Entidad Pension Nueva',
            'fecha_cambio' => 'Fecha Cambio',
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
    public function getEntidadPensionAnterior()
    {
        return $this->hasOne(EntidadPension::className(), ['id_entidad_pension' => 'id_entidad_pension_anterior']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntidadPensionNueva()
    {
        return $this->hasOne(EntidadPension::className(), ['id_entidad_pension' => 'id_entidad_pension_nueva']);
    }
}
