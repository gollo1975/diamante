<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_rack".
 *
 * @property int $id_rack
 * @property int $numero_rack
 * @property string $descripcion
 * @property string $medida_ancho
 * @property string $media_alto
 * @property double $total_peso
 * @property string $user_name
 */
class TipoRack extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_rack';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_rack'], 'integer'],
            [['descripcion', 'user_name'], 'required'],
            [['medida_ancho', 'media_alto', 'total_peso'], 'number'],
            [['descripcion'], 'string', 'max' => 40],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rack' => 'Id Rack',
            'numero_rack' => 'Numero Rack',
            'descripcion' => 'Descripcion',
            'medida_ancho' => 'Medida Ancho',
            'media_alto' => 'Media Alto',
            'total_peso' => 'Total Peso',
            'user_name' => 'User Name',
        ];
    }
}
