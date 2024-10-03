<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seguimiento_incapacidad".
 *
 * @property int $id_seguimiento
 * @property int $id_incapacidad
 * @property string $nota
 * @property string $fecha_proceso
 * @property string $user_name
 *
 * @property Incapacidad $incapacidad
 */
class SeguimientoIncapacidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seguimiento_incapacidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_incapacidad', 'nota', 'user_name'], 'required'],
            [['id_incapacidad'], 'integer'],
            [['nota'], 'string'],
            [['fecha_proceso'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_incapacidad'], 'exist', 'skipOnError' => true, 'targetClass' => Incapacidad::className(), 'targetAttribute' => ['id_incapacidad' => 'id_incapacidad']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_seguimiento' => 'Id Seguimiento',
            'id_incapacidad' => 'Id Incapacidad',
            'nota' => 'Nota',
            'fecha_proceso' => 'Fecha Proceso',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIncapacidad()
    {
        return $this->hasOne(Incapacidad::className(), ['id_incapacidad' => 'id_incapacidad']);
    }
}
