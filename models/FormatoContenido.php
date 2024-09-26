<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "formato_contenido".
 *
 * @property int $id_formato_contenido
 * @property string $nombre_formato
 * @property string $contenido
 * @property int $id_configuracion_prefijo
 * @property string $fecha_creacion
 * @property string $user_name
 *
 * @property ConfiguracionFormatoPrefijo $configuracionPrefijo
 */
class FormatoContenido extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formato_contenido';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_formato', 'contenido', 'id_configuracion_prefijo'], 'required'],
            [['contenido'], 'string'],
            [['id_configuracion_prefijo'], 'integer'],
            [['fecha_creacion'], 'safe'],
            [['nombre_formato'], 'string', 'max' => 70],
            [['user_name'], 'string', 'max' => 15],
            [['id_configuracion_prefijo'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionFormatoPrefijo::className(), 'targetAttribute' => ['id_configuracion_prefijo' => 'id_configuracion_prefijo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_formato_contenido' => 'Id Formato Contenido',
            'nombre_formato' => 'Nombre Formato',
            'contenido' => 'Contenido',
            'id_configuracion_prefijo' => 'Id Configuracion Prefijo',
            'fecha_creacion' => 'Fecha Creacion',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracionPrefijo()
    {
        return $this->hasOne(ConfiguracionFormatoPrefijo::className(), ['id_configuracion_prefijo' => 'id_configuracion_prefijo']);
    }
}
