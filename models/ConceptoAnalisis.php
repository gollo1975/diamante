<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concepto_analisis".
 *
 * @property int $id_analisis
 * @property string $concepto
 * @property string $fecha_registro
 * @property string $user_name
 */
class ConceptoAnalisis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concepto_analisis';
    }

     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->concepto = strtoupper($this->concepto); 
 
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['id_etapa'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['concepto'], 'string', 'max' => 40],
            [['user_name'], 'string', 'max' => 15],
            [['id_etapa'], 'exist', 'skipOnError' => true, 'targetClass' => EtapasAuditoria::className(), 'targetAttribute' => ['id_etapa' => 'id_etapa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_analisis' => 'Codigo:',
            'concepto' => 'Concepto:',
            'fecha_registro' => 'Fecha registro',
            'user_name' => 'User Name',
            'id_etapa' => 'Etapa:',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtapaProceso()
    {
        return $this->hasOne(EtapasAuditoria::className(), ['id_etapa' => 'id_etapa']);
    }
}
