<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "diagnostico_incapacidad".
 *
 * @property int $id_codigo
 * @property string $codigo_diagnostico
 * @property string $diagnostico
 * @property string $user_name
 */
class DiagnosticoIncapacidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diagnostico_incapacidad';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->diagnostico = strtoupper($this->diagnostico); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_diagnostico', 'diagnostico'], 'required'],
            [['codigo_diagnostico'], 'string', 'max' => 10],
            [['diagnostico'], 'string', 'max' => 100],
            [['user_name'], 'string', 'max' => 15],
            [['codigo_diagnostico'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_codigo' => 'Id',
            'codigo_diagnostico' => 'Codigo',
            'diagnostico' => 'Diagnostico',
            'user_name' => 'User Name',
        ];
    }
    
     //proceso que agrupa varios campos
    public function getDiagnosticoCompleto()
    {
        return " Codigo: {$this->codigo_diagnostico} - Nombre: {$this->diagnostico}";
    }
}
