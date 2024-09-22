<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grupo_sanguineo".
 *
 * @property int $id_grupo
 * @property string $descripcion
 * @property string $clasificacion
 */
class GrupoSanguineo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grupo_sanguineo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'clasificacion'], 'required'],
            [['descripcion'], 'string', 'max' => 15],
            [['clasificacion'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_grupo' => 'Id Grupo',
            'descripcion' => 'Descripcion',
            'clasificacion' => 'Clasificacion',
        ];
    }
}
