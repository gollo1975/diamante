<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_visita_comercial".
 *
 * @property int $id_tipo_visita
 * @property int $nombre_visita
 * @property string $fecha_registro
 */
class TipoVisitaComercial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_visita_comercial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_visita'], 'required'],
            [['nombre_visita'], 'integer'],
            [['fecha_registro'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_visita' => 'Id Tipo Visita',
            'nombre_visita' => 'Nombre Visita',
            'fecha_registro' => 'Fecha Registro',
        ];
    }
}
