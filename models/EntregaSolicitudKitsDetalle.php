<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrega_solicitud_kits_detalle".
 *
 * @property int $id_detalle_entrega
 * @property int $id_entrega_kits
 * @property int $id_detalle
 * @property int $cantidad_solicitada
 * @property int $cantidad_despachada
 *
 * @property EntregaSolicitudKits $entregaKits
 * @property SolicitudArmadoKitsDetalle $detalle
 */
class EntregaSolicitudKitsDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrega_solicitud_kits_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_detalle_entrega'], 'required'],
            [['id_detalle_entrega', 'id_entrega_kits', 'id_detalle', 'cantidad_solicitada', 'cantidad_despachada','unidades_faltante','solicitud_empaque'], 'integer'],
            [['id_detalle_entrega'], 'unique'],
            ['numero_lote' , 'string'],
            [['id_entrega_kits'], 'exist', 'skipOnError' => true, 'targetClass' => EntregaSolicitudKits::className(), 'targetAttribute' => ['id_entrega_kits' => 'id_entrega_kits']],
            [['id_detalle'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudArmadoKitsDetalle::className(), 'targetAttribute' => ['id_detalle' => 'id_detalle']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle_entrega' => 'Id Detalle Entrega',
            'id_entrega_kits' => 'Id Entrega Kits',
            'id_detalle' => 'Id Detalle',
            'cantidad_solicitada' => 'Cantidad Solicitada',
            'cantidad_despachada' => 'Cantidad Despachada',
            'unidades_faltante' => 'unidades_faltante',
            'numero_lote' => 'Numero lote',
            'solicitud_empaque' => 'solicitud_empaque',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntregaKits()
    {
        return $this->hasOne(EntregaSolicitudKits::className(), ['id_entrega_kits' => 'id_entrega_kits']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalle()
    {
        return $this->hasOne(SolicitudArmadoKitsDetalle::className(), ['id_detalle' => 'id_detalle']);
    }
}
