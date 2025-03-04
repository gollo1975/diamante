<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solicitud_materiales_detalle".
 *
 * @property int $id
 * @property int $codigo
 * @property int $id_materia_prima
 * @property string $codigo_materia
 * @property string $materiales
 * @property int $unidades_lote
 * @property int $unidades_requeridas
 *
 * @property SolicitudMateriales $codigo0
 * @property MateriaPrimas $materiaPrima
 */
class SolicitudMaterialesDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud_materiales_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'id_materia_prima', 'unidades_lote', 'unidades_requeridas','id_detalle'], 'integer'],
            [['codigo_materia'], 'string', 'max' => 15],
            [['materiales'], 'string', 'max' => 30],
            [['codigo'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudMateriales::className(), 'targetAttribute' => ['codigo' => 'codigo']],
            [['id_materia_prima'], 'exist', 'skipOnError' => true, 'targetClass' => MateriaPrimas::className(), 'targetAttribute' => ['id_materia_prima' => 'id_materia_prima']],
            [['id_detalle'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccionProductos::className(), 'targetAttribute' => ['id_detalle' => 'id_detalle']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'id_materia_prima' => 'Id Materia Prima',
            'codigo_materia' => 'Codigo Materia',
            'materiales' => 'Materiales',
            'unidades_lote' => 'Unidades Lote',
            'unidades_requeridas' => 'Unidades Requeridas',
            'id_detalle' => 'id_detalle,'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigo()
    {
        return $this->hasOne(SolicitudMateriales::className(), ['codigo' => 'codigo']);
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
    public function getOrdenPresentacion()
    {
        return $this->hasOne(OrdenProduccionProductos::className(), ['id_detalle' => 'id_detalle']);
    }
}
