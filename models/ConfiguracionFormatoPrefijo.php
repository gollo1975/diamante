<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_formato_prefijo".
 *
 * @property int $id_configuracion_prefijo
 * @property string $formato
 * @property int $estado_formato
 */
class ConfiguracionFormatoPrefijo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_formato_prefijo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['formato'], 'required'],
            [['estado_formato'], 'integer'],
            [['formato'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_configuracion_prefijo' => 'Id Configuracion Prefijo',
            'formato' => 'Formato',
            'estado_formato' => 'Estado Formato',
        ];
    }
}
