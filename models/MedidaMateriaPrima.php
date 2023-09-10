<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "medida_materia_prima".
 *
 * @property int $id_medida
 * @property string $descripcion
 *
 * @property MateriaPrimas[] $materiaPrimas
 */
class MedidaMateriaPrima extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medida_materia_prima';
    }
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->descripcion = strtoupper($this->descripcion); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_medida' => 'Código',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateriaPrimas()
    {
        return $this->hasMany(MateriaPrimas::className(), ['id_medida' => 'id_medida']);
    }
}
