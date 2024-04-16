<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_produccion_auditoria_fabricacion_detalle".
 *
 * @property int $id_detalle
 * @property int $id_auditoria
 * @property int $id_analisis
 * @property int $id_especificacion
 * @property string $resultado
 * @property int $continua_proceso 0. NO 1. NO
 * @property int $condicion_analisis 0. Aprobado, 1. rechazado
 *
 * @property OrdenProduccionAuditoriaFabricacion $auditoria
 * @property ConceptoAnalisis $analisis
 * @property EspecificacionProducto $especificacion
 */
class OrdenProduccionAuditoriaFabricacionDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_produccion_auditoria_fabricacion_detalle';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->resultado = strtoupper($this->resultado); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_auditoria', 'id_analisis', 'id_especificacion'], 'integer'],
            [['resultado'], 'string', 'max' => 15],
            [['id_auditoria'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccionAuditoriaFabricacion::className(), 'targetAttribute' => ['id_auditoria' => 'id_auditoria']],
            [['id_analisis'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoAnalisis::className(), 'targetAttribute' => ['id_analisis' => 'id_analisis']],
            [['id_especificacion'], 'exist', 'skipOnError' => true, 'targetClass' => EspecificacionProducto::className(), 'targetAttribute' => ['id_especificacion' => 'id_especificacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_auditoria' => 'Id Auditoria',
            'id_analisis' => 'Id Analisis',
            'id_especificacion' => 'Id Especificacion',
            'resultado' => 'Resultado',
           
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuditoria()
    {
        return $this->hasOne(OrdenProduccionAuditoriaFabricacion::className(), ['id_auditoria' => 'id_auditoria']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalisis()
    {
        return $this->hasOne(ConceptoAnalisis::className(), ['id_analisis' => 'id_analisis']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEspecificacion()
    {
        return $this->hasOne(EspecificacionProducto::className(), ['id_especificacion' => 'id_especificacion']);
    }
}
