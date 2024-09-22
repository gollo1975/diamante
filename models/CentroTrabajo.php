<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "centro_trabajo".
 *
 * @property int $id_centro_trabajo
 * @property string $centro_trabajo
 * @property int $estado
 * @property string $user_name
 */
class CentroTrabajo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'centro_trabajo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['centro_trabajo'], 'required'],
            [['estado'], 'integer'],
            [['centro_trabajo'], 'string', 'max' => 40],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_centro_trabajo' => 'Id Centro Trabajo',
            'centro_trabajo' => 'Centro Trabajo',
            'estado' => 'Estado',
            'user_name' => 'User Name',
        ];
    }
}
