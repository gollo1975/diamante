<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrada_materia_prima_detalle".
 *
 * @property int $id_detalle
 * @property int $id_entrada
 * @property int $id_materia_prima
 * @property double $porcentaje_iva
 * @property int $cantidad
 * @property double $valor_unitario
 * @property int $total_iva
 * @property int $subtotal
 * @property int $total_entrada
 *
 * @property EntradaMateriaPrima $entrada
 * @property MateriaPrimas $materiaPrima
 */
class EntradaMateriaPrimaDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrada_materia_prima_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entrada', 'id_materia_prima', 'cantidad', 'total_iva', 'subtotal', 'total_entrada','valor_unitario','actualizar_precio'], 'integer'],
            [['porcentaje_iva'], 'number'],
            [['fecha_vencimiento'], 'safe'],
            [['id_entrada'], 'exist', 'skipOnError' => true, 'targetClass' => EntradaMateriaPrima::className(), 'targetAttribute' => ['id_entrada' => 'id_entrada']],
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
            'id_entrada' => 'Id Entrada',
            'id_materia_prima' => 'Id Materia Prima',
            'porcentaje_iva' => 'Porcentaje Iva',
            'cantidad' => 'Cantidad',
            'valor_unitario' => 'Valor Unitario',
            'total_iva' => 'Total Iva',
            'subtotal' => 'Subtotal',
            'total_entrada' => 'Total Entrada',
            'actualizar_precio' => 'Actualizar precio',
            'fecha_vencimiento' => 'Fecha vcto:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntrada()
    {
        return $this->hasOne(EntradaMateriaPrima::className(), ['id_entrada' => 'id_entrada']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateriaPrima()
    {
        return $this->hasOne(MateriaPrimas::className(), ['id_materia_prima' => 'id_materia_prima']);
    }
    
    public function getActualizarPrecio() {
        if($this->actualizar_precio == 0){
            $actualizarprecio = 'NO';
        }else{
            $actualizarprecio = 'SI';
        }
        return $actualizarprecio;
    }
}
