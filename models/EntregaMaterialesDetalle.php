<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrega_materiales_detalle".
 *
 * @property int $id
 * @property int $id_entrega
 * @property int $id_materia_prima
 * @property string $codigo_materia
 * @property string $materiales
 * @property int $unidades_solicitadas
 * @property int $unidades_despachadas
 *
 * @property EntregaMateriales $entrega
 * @property MateriaPrimas $materiaPrima
 */
class EntregaMaterialesDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrega_materiales_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entrega', 'id_materia_prima', 'unidades_solicitadas', 'unidades_despachadas','id_detalle'], 'integer'],
            [['codigo_materia'], 'string', 'max' => 15],
            [['materiales'], 'string', 'max' => 30],
            [['id_entrega'], 'exist', 'skipOnError' => true, 'targetClass' => EntregaMateriales::className(), 'targetAttribute' => ['id_entrega' => 'id_entrega']],
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
            'id_entrega' => 'Id Entrega',
            'id_materia_prima' => 'Id Materia Prima',
            'codigo_materia' => 'Codigo Materia',
            'materiales' => 'Materiales',
            'unidades_solicitadas' => 'Unidades Solicitadas',
            'unidades_despachadas' => 'Unidades Despachadas',
            'id_detalle' => 'id_detalle',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntrega()
    {
        return $this->hasOne(EntregaMateriales::className(), ['id_entrega' => 'id_entrega']);
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
    public function getOrdenProductos()
    {
        return $this->hasOne(OrdenProduccionProductos::className(), ['id_detalle' => 'id_detalle']);
    }
}
