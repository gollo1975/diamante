<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_estudio".
 *
 * @property int $id_tipo_estudio
 * @property string $estudio
 *
 * @property EstudioEmpleado[] $estudioEmpleados
 */
class TipoEstudio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_estudio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estudio'], 'required'],
            [['estudio'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_estudio' => 'Id Tipo Estudio',
            'estudio' => 'Estudio',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudioEmpleados()
    {
        return $this->hasMany(EstudioEmpleado::className(), ['id_tipo_estudio' => 'id_tipo_estudio']);
    }
}
