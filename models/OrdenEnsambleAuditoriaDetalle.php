<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_ensamble_auditoria_detalle".
 *
 * @property int $id_detalle
 * @property int $id_auditoria
 * @property int $id_analisis
 * @property int $id_especificacion
 * @property string $resultado
 *
 * @property OrdenEnsambleAuditoria $auditoria
 * @property ConceptoAnalisis $analisis
 * @property EspecificacionProducto $especificacion
 */
class OrdenEnsambleAuditoriaDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_ensamble_auditoria_detalle';
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
            [['id_detalle'], 'required'],
            [['id_detalle', 'id_auditoria', 'id_analisis', 'id_especificacion'], 'integer'],
            [['resultado'], 'string', 'max' => 15],
            [['id_detalle'], 'unique'],
            [['id_auditoria'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenEnsambleAuditoria::className(), 'targetAttribute' => ['id_auditoria' => 'id_auditoria']],
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
        return $this->hasOne(OrdenEnsambleAuditoria::className(), ['id_auditoria' => 'id_auditoria']);
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
