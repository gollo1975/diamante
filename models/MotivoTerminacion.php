<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "motivo_terminacion".
 *
 * @property int $id_motivo_terminacion
 * @property string $motivo
 */
class MotivoTerminacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'motivo_terminacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['motivo'], 'required'],
            [['motivo'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_motivo_terminacion' => 'Id Motivo Terminacion',
            'motivo' => 'Motivo',
        ];
    }
}
