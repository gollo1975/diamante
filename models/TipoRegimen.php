<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_regimen".
 *
 * @property int $id_tipo_regimen
 * @property string $regimen
 */
class TipoRegimen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_regimen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['regimen'], 'required'],
            [['regimen'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_regimen' => 'Id Tipo Regimen',
            'regimen' => 'Regimen',
        ];
    }
}
