<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "indicador_comercial_clientes".
 *
 * @property int $id_detalle
 * @property int $id_cliente
 * @property int $id_indicador
 * @property int $id_agente
 * @property int $total_visitas
 * @property int $visita_real
 * @property int $visita_no_real
 * @property double $porcentaje
 * @property string $fecha_hora
 * @property string $desde
 * @property string $hasta
 *
 * @property Clientes $cliente
 * @property IndicadorComercial $indicador
 * @property AgentesComerciales $agente
 */
class IndicadorComercialClientes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'indicador_comercial_clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_indicador', 'id_agente', 'total_visitas', 'visita_real', 'visita_no_real'], 'integer'],
            [['porcentaje'], 'number'],
            [['fecha_hora', 'desde', 'hasta'], 'safe'],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_indicador'], 'exist', 'skipOnError' => true, 'targetClass' => IndicadorComercial::className(), 'targetAttribute' => ['id_indicador' => 'id_indicador']],
            [['id_agente'], 'exist', 'skipOnError' => true, 'targetClass' => AgentesComerciales::className(), 'targetAttribute' => ['id_agente' => 'id_agente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_cliente' => 'Id Cliente',
            'id_indicador' => 'Id Indicador',
            'id_agente' => 'Id Agente',
            'total_visitas' => 'Total Visitas',
            'visita_real' => 'Visita Real',
            'visita_no_real' => 'Visita No Real',
            'porcentaje' => 'Porcentaje',
            'fecha_hora' => 'Fecha Hora',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndicador()
    {
        return $this->hasOne(IndicadorComercial::className(), ['id_indicador' => 'id_indicador']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgente()
    {
        return $this->hasOne(AgentesComerciales::className(), ['id_agente' => 'id_agente']);
    }
}
