<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "consecutivos".
 *
 * @property int $id_consecutivo
 * @property string $concepto
 * @property int $numero_inicial
 */
class Consecutivos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consecutivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto', 'numero_inicial'], 'required'],
            [['numero_inicial'], 'integer'],
            [['concepto'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_consecutivo' => 'Id Consecutivo',
            'concepto' => 'Concepto',
            'numero_inicial' => 'Numero Inicial',
        ];
    }
}
