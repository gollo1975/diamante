<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "arl".
 *
 * @property int $id_arl
 * @property string $dscripcion
 * @property double $porcentaje
 */
class Arl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'arl';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'porcentaje'], 'required'],
            [['porcentaje'], 'number'],
            [['dscripcion'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_arl' => 'Id Arl',
            'descripcion' => 'Dscripcion',
            'porcentaje' => 'Porcentaje',
        ];
    }
    public function getCompleto() {
        return " {$this->descripcion} - {$this->porcentaje} %";
        
    }
}
