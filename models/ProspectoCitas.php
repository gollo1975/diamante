<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prospecto_citas".
 *
 * @property int $id_cita_prospecto
 * @property int $id_prospecto
 * @property int $id_tipo_visita
 * @property string $hora_cita
 * @property string $fecha_registro
 * @property string $user_name
 * @property string $nota
 *
 * @property ClienteProspecto $prospecto
 * @property TipoVisitaComercial $tipoVisita
 */
class ProspectoCitas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prospecto_citas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_prospecto', 'id_tipo_visita','id_agente'], 'integer'],
            [['hora_cita'], 'string'],
            [['fecha_registro','fecha_cita'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['nota'], 'string', 'max' => 100],
            [['id_prospecto'], 'exist', 'skipOnError' => true, 'targetClass' => ClienteProspecto::className(), 'targetAttribute' => ['id_prospecto' => 'id_prospecto']],
            [['id_tipo_visita'], 'exist', 'skipOnError' => true, 'targetClass' => TipoVisitaComercial::className(), 'targetAttribute' => ['id_tipo_visita' => 'id_tipo_visita']],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cita_prospecto' => 'Id Cita Prospecto',
            'id_prospecto' => 'Id Prospecto',
            'id_tipo_visita' => 'Id Tipo Visita',
            'hora_cita' => 'Hora Cita',
            'fecha_registro' => 'Fecha Registro',
            'user_name' => 'User Name',
            'nota' => 'Nota',
            'fecha_cita' => 'fecha_cita',
            'id_agente' => 'Vendedor:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProspecto()
    {
        return $this->hasOne(ClienteProspecto::className(), ['id_prospecto' => 'id_prospecto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoVisita()
    {
        return $this->hasOne(TipoVisitaComercial::className(), ['id_tipo_visita' => 'id_tipo_visita']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
     public function getAgenteCita()
    {
        return $this->hasOne(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }
}
