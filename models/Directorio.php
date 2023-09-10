<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "directorio".
 *
 * @property int $iddirectorio
 * @property string $nombre
 * @property int $numero
 * @property int $numeroarchivos
 * @property string $ruta
 *
 * @property DirectorioArchivos[] $directorioArchivos
 */
class Directorio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'directorio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero', 'numeroarchivos'], 'integer'],
            [['nombre'], 'string', 'max' => 50],
            [['ruta'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddirectorio' => 'Iddirectorio',
            'nombre' => 'Nombre',
            'numero' => 'Numero',
            'numeroarchivos' => 'Numeroarchivos',
            'ruta' => 'Ruta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorioArchivos()
    {
        return $this->hasMany(DirectorioArchivos::className(), ['iddirectorio' => 'iddirectorio']);
    }
}
