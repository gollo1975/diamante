<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_dotacion".
 *
 * @property int $id_tipo_dotacion
 * @property string $descripcion
 *
 * @property EntregaDotacion[] $entregaDotacions
 */
class TipoDotacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_dotacion';
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
            'id_tipo_dotacion' => 'Id Tipo Dotacion',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntregaDotacions()
    {
        return $this->hasMany(EntregaDotacion::className(), ['id_tipo_dotacion' => 'id_tipo_dotacion']);
    }
}
