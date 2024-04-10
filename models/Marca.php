<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "marca".
 *
 * @property int $id_marca
 * @property string $marca
 */
class Marca extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'marca';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['marca'], 'required'],
            [['marca'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_marca' => 'Id Marca',
            'marca' => 'Marca',
        ];
    }
}
