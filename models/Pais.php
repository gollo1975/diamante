<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pais".
 *
 * @property string $codigo_pais
 * @property string $pais
 * @property string $codigo_interfaz
 *
 * @property Departamentos[] $departamentos
 */
class Pais extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pais';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_pais', 'pais'], 'required'],
            [['codigo_pais', 'codigo_interfaz'], 'string', 'max' => 10],
            [['pais'], 'string', 'max' => 30],
            [['usuario_creador'], 'string', 'max' => 15],
            [['codigo_pais'], 'unique'],
            [['fecha_creacion'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo_pais' => 'Codigo',
            'pais' => 'Nombre pais',
            'codigo_interfaz' => 'Codigo Interfaz',
            'fecha_creacion' => 'Fecha cracion',
            'usuario_creador' => 'User name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartamentos()
    {
        return $this->hasMany(Departamentos::className(), ['codigo_pais' => 'codigo_pais']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getPais()
    {
        return $this->hasMany(Municipios::className(), ['codigo_pais' => 'codigo_pais']);
    }
}
