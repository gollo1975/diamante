<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zona_clientes".
 *
 * @property int $id_zona
 * @property int $nombre_zona
 * @property string $codigo_interface
 */
class ZonaClientes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zona_clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_zona', 'codigo_interface'], 'required'],
            [['nombre_zona'], 'string', 'max' => 40],
            [['codigo_interface'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_zona' => 'Id Zona',
            'nombre_zona' => 'Nombre Zona',
            'codigo_interface' => 'Codigo Interface',
        ];
    }
}
