<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_credito".
 *
 * @property int $codigo_credito
 * @property string $nombre_credito
 * @property int $codigo_salario
 */
class ConfiguracionCredito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_credito';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombre_credito = strtoupper($this->nombre_credito); 

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_credito', 'codigo_salario'], 'required'],
            [['codigo_salario'], 'integer'],
            [['nombre_credito'], 'string', 'max' => 50],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo_credito' => 'Codigo',
            'nombre_credito' => 'Tipo de credito',
            'codigo_salario' => 'Concepto de salario',
        ];
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getConceptoSalarios()
    {
        return $this->hasOne(ConceptoSalarios::className(), ['codigo_salario' => 'codigo_salario']);
    }
}
