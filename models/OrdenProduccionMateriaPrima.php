<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_produccion_materia_prima".
 *
 * @property int $id_detalle
 * @property int $id_orden_produccion
 * @property int $id_materia_prima
 * @property int $cantidad
 * @property double $porcentaje_iva
 * @property int $valor_iva
 * @property int $subtotal
 * @property int $total
 * @property string $fecha_registro
 *
 * @property OrdenProduccion $ordenProduccion
 * @property MateriaPrimas $materiaPrima
 */
class OrdenProduccionMateriaPrima extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_produccion_materia_prima';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_produccion', 'id_materia_prima', 'cantidad', 'valor_iva', 'subtotal', 'total', 'valor_unitario', 'importado'], 'integer'],
            [['porcentaje_iva'], 'number'],
            [['fecha_registro'], 'safe'],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id_materia_prima'], 'exist', 'skipOnError' => true, 'targetClass' => MateriaPrimas::className(), 'targetAttribute' => ['id_materia_prima' => 'id_materia_prima']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_orden_produccion' => 'Id Orden Produccion',
            'id_materia_prima' => 'Id Materia Prima',
            'cantidad' => 'Cantidad',
            'porcentaje_iva' => 'Porcentaje Iva',
            'valor_iva' => 'Valor Iva',
            'subtotal' => 'Subtotal',
            'total' => 'Total',
            'fecha_registro' => 'Fecha Registro',
            'valor_unitario' => 'valor_unitario',
            'importado' => 'importado',
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
    public function getMateriaPrima()
    {
        return $this->hasOne(MateriaPrimas::className(), ['id_materia_prima' => 'id_materia_prima']);
    }
    
    public function getDocumentoExportado() {
        if($this->importado == 0){
           $documentoexportado = 'NO';
        }else{
            $documentoexportado = 'SI';
        }
        return $documentoexportado;
    }
}
