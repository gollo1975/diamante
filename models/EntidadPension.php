<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entidad_pension".
 *
 * @property int $id_entidad_pension
 * @property string $entidad
 * @property int $estado
 */
class EntidadPension extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entidad_pension';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_entidad_pension', 'entidad'], 'required'],
            [['id_entidad_pension', 'estado'], 'integer'],
            [['entidad'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_entidad_pension' => 'Id Entidad Pension',
            'entidad' => 'Entidad',
            'estado' => 'Estado',
        ];
    }
}
