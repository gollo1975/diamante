<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bitacora_materias_primas".
 *
 * @property int $id_salida
 * @property int $id_materia_prima
 * @property int $cantidad
 * @property string $fecha_salida
 * @property string $fecha_hora_salida
 * @property string $user_name
 * @property string $descripcion_salida
 * @property int $id_orden_produccion
 * @property int $id_entrega_kits
 *
 * @property MateriaPrimas $materiaPrima
 * @property OrdenProduccion $ordenProduccion
 * @property EntregaSolicitudKits $entregaKits
 */
class BitacoraMateriasPrimas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bitacora_materias_primas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_materia_prima', 'cantidad', 'id_orden_produccion', 'id_entrega_kits'], 'integer'],
            [['fecha_salida', 'fecha_hora_salida'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['descripcion_salida'], 'string', 'max' => 57],
            [['id_materia_prima'], 'exist', 'skipOnError' => true, 'targetClass' => MateriaPrimas::className(), 'targetAttribute' => ['id_materia_prima' => 'id_materia_prima']],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id_entrega_kits'], 'exist', 'skipOnError' => true, 'targetClass' => EntregaSolicitudKits::className(), 'targetAttribute' => ['id_entrega_kits' => 'id_entrega_kits']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_salida' => 'Id Salida',
            'id_materia_prima' => 'Id Materia Prima',
            'cantidad' => 'Cantidad',
            'fecha_salida' => 'Fecha Salida',
            'fecha_hora_salida' => 'Fecha Hora Salida',
            'user_name' => 'User Name',
            'descripcion_salida' => 'Descripcion Salida',
            'id_orden_produccion' => 'Id Orden Produccion',
            'id_entrega_kits' => 'Id Entrega Kits',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateriaPrima()
    {
        return $this->hasOne(MateriaPrimas::className(), ['id_materia_prima' => 'id_materia_prima']);
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
    public function getEntregaKits()
    {
        return $this->hasOne(EntregaSolicitudKits::className(), ['id_entrega_kits' => 'id_entrega_kits']);
    }
}
