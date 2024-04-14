<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "etapas_auditoria".
 *
 * @property int $id_etapa
 * @property string $concepto
 */
class EtapasAuditoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'etapas_auditoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['concepto'], 'string', 'max' => 30],
            [['color'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_etapa' => 'Id Etapa',
            'concepto' => 'Concepto',
            'color' => 'color',
        ];
    }
}
