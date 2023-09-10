<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "area_empresa".
 *
 * @property int $id_area
 * @property string $descripcion
 */
class AreaEmpresa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_empresa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_area' => 'Id Area',
            'descripcion' => 'Descripcion',
        ];
    }
}
