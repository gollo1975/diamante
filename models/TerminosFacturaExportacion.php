<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "terminos_factura_exportacion".
 *
 * @property int $id_terminos
 * @property int $id_inconterm
 * @property int $medio_transporte
 * @property string $ciudad_origen
 * @property string $ciudad_destino
 * @property double $peso_neto
 * @property double $peso_bruto
 * @property int $id_medida_producto
 * @property string $user_name
 * @property int $id_factura
 *
 * @property IncotermFactura $inconterm
 * @property Municipios $ciudadOrigen
 * @property MedidaProductoTerminado $medidaProducto
 * @property FacturaVenta $factura
 */
class TerminosFacturaExportacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'terminos_factura_exportacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inconterm', 'medio_transporte', 'id_medida_producto', 'id_factura','codigo_pais'], 'integer'],
            [['peso_neto', 'peso_bruto'], 'number'],
            [['ciudad_origen'], 'string', 'max' => 10],
            [['ciudad_destino'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
            [['id_inconterm'], 'exist', 'skipOnError' => true, 'targetClass' => IncotermFactura::className(), 'targetAttribute' => ['id_inconterm' => 'id_inconterm']],
            [['ciudad_origen'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['ciudad_origen' => 'codigo_municipio']],
            [['id_medida_producto'], 'exist', 'skipOnError' => true, 'targetClass' => MedidaProductoTerminado::className(), 'targetAttribute' => ['id_medida_producto' => 'id_medida_producto']],
            [['id_factura'], 'exist', 'skipOnError' => true, 'targetClass' => FacturaVenta::className(), 'targetAttribute' => ['id_factura' => 'id_factura']],
            [['codigo_pais'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::className(), 'targetAttribute' => ['codigo_pais' => 'codigo_pais']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_terminos' => 'Id Terminos',
            'id_inconterm' => 'Id Inconterm',
            'medio_transporte' => 'Medio Transporte',
            'ciudad_origen' => 'Ciudad Origen',
            'ciudad_destino' => 'Ciudad Destino',
            'peso_neto' => 'Peso Neto',
            'peso_bruto' => 'Peso Bruto',
            'id_medida_producto' => 'Id Medida Producto',
            'user_name' => 'User Name',
            'id_factura' => 'Id Factura',
            'codigo_pais' => 'Nombre del pais',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInconterm()
    {
        return $this->hasOne(IncotermFactura::className(), ['id_inconterm' => 'id_inconterm']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCiudadOrigen()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'ciudad_origen']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPais()
    {
        return $this->hasOne(Pais::className(), ['codigo_pais' => 'codigo_pais']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedidaProducto()
    {
        return $this->hasOne(MedidaProductoTerminado::className(), ['id_medida_producto' => 'id_medida_producto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactura()
    {
        return $this->hasOne(FacturaVenta::className(), ['id_factura' => 'id_factura']);
    }
}
