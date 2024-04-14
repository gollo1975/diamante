<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concepto_analisis".
 *
 * @property int $id_analisis
 * @property string $concepto
 * @property string $fecha_registro
 * @property string $user_name
 */
class ConceptoAnalisis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concepto_analisis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['fecha_registro'], 'safe'],
            [['concepto'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_analisis' => 'Id Analisis',
            'concepto' => 'Concepto',
            'fecha_registro' => 'Fecha Registro',
            'user_name' => 'User Name',
        ];
    }
}
