<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "incoterm_factura".
 *
 * @property int $id_inconterm
 * @property string $concepto
 */
class IncotermFactura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incoterm_factura';
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
            'id_inconterm' => 'Id Inconterm',
            'concepto' => 'Concepto',
        ];
    }
}
