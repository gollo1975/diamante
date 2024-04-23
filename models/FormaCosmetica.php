<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "forma_cosmetica".
 *
 * @property int $id_forma
 * @property string $concepto
 *
 * @property OrdenEnsambleAuditoria[] $ordenEnsambleAuditorias
 */
class FormaCosmetica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forma_cosmetica';
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
            'id_forma' => 'Id Forma',
            'concepto' => 'Concepto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenEnsambleAuditorias()
    {
        return $this->hasMany(OrdenEnsambleAuditoria::className(), ['id_forma' => 'id_forma']);
    }
}
