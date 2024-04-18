<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_ensamble_producto".
 *
 * @property int $id_ensamble
 * @property int $id_orden_produccion
 * @property int $numero_orden_ensamble
 * @property int $id_grupo
 * @property int $numero_lote
 * @property int $id_etapa
 * @property string $fecha_proceso
 * @property string $fecha_hora_registro
 * @property string $user_name
 * @property int $peso_neto
 * @property string $observacion
 * @property string $responsable
 *
 * @property OrdenProduccion $ordenProduccion
 * @property EtapasAuditoria $etapa
 * @property GrupoProducto $grupo
 * @property OrdenEnsambleProductoDetalle[] $ordenEnsambleProductoDetalles
 */
class OrdenEnsambleProducto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_ensamble_producto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_produccion', 'numero_orden_ensamble', 'id_grupo', 'numero_lote', 'id_etapa', 'peso_neto','autorizado','total_unidades'], 'integer'],
            [['fecha_proceso', 'fecha_hora_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 100],
            [['responsable'], 'string', 'max' => 50],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id_etapa'], 'exist', 'skipOnError' => true, 'targetClass' => EtapasAuditoria::className(), 'targetAttribute' => ['id_etapa' => 'id_etapa']],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_ensamble' => 'Id',
            'id_orden_produccion' => 'Codigo produccion:',
            'numero_orden_ensamble' => 'Numero de ensamble:',
            'id_grupo' => 'Grupo:',
            'numero_lote' => 'Numero lote:',
            'id_etapa' => 'Etapa:',
            'fecha_proceso' => 'Fecha proceso:',
            'fecha_hora_registro' => 'Fecha hora registro:',
            'user_name' => 'User name:',
            'peso_neto' => 'Peso neto:',
            'observacion' => 'Observacion:',
            'responsable' => 'Responsable:',
            'autorizado' => 'Autorizado:',
            'total_unidades' => 'Unidades proyectadas:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenProduccion()
    {
        return $this->hasOne(OrdenProduccion::className(), ['id_orden_produccion' => 'id_orden_produccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtapa()
    {
        return $this->hasOne(EtapasAuditoria::className(), ['id_etapa' => 'id_etapa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(GrupoProducto::className(), ['id_grupo' => 'id_grupo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenEnsambleProductoDetalles()
    {
        return $this->hasMany(OrdenEnsambleProductoDetalle::className(), ['id_ensamble' => 'id_ensamble']);
    }
}
