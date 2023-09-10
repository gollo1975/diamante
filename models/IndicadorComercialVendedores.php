<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "indicador_comercial_vendedores".
 *
 * @property int $id
 * @property int $id_agente
 * @property string $documento
 * @property string $agente
 * @property int $id_indicador
 * @property int $total_visitas
 * @property int $total_realizadas
 * @property int $total_no_realizadas
 * @property int $total_porcentaje
 * @property string $fecha_hora
 *
 * @property AgentesComerciales $agente0
 * @property IndicadorComercial $indicador
 */
class IndicadorComercialVendedores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'indicador_comercial_vendedores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_agente', 'id_indicador', 'total_visitas', 'total_realizadas', 'total_no_realizadas', 'total_porcentaje'], 'integer'],
            [['fecha_hora','desde','hasta'], 'safe'],
            [['documento'], 'string', 'max' => 15],
            [['agente'], 'string', 'max' => 40],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
            [['id_indicador'], 'exist', 'skipOnError' => true, 'targetClass' => IndicadorComercial::className(), 'targetAttribute' => ['id_indicador' => 'id_indicador']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_agente' => 'Id Agente',
            'documento' => 'Documento',
            'agente' => 'Agente',
            'id_indicador' => 'Id Indicador',
            'total_visitas' => 'Total Visitas',
            'total_realizadas' => 'Total Realizadas',
            'total_no_realizadas' => 'Total No Realizadas',
            'total_porcentaje' => 'Total Porcentaje',
            'fecha_hora' => 'Fecha Hora',
            'desde' => 'Desde:',
            'hasta' => 'Hasta:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgenteComercial()
    {
        return $this->hasOne(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndicador()
    {
        return $this->hasOne(IndicadorComercial::className(), ['id_indicador' => 'id_indicador']);
    }
}
