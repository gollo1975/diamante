<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "motivo_nota_credito".
 *
 * @property int $id_motivo
 * @property string $cencepto
 */
class MotivoNotaCredito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'motivo_nota_credito';
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
            [['concepto'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_motivo' => 'Codigo',
            'concepto' => 'Concepto',
        ];
    }
}
