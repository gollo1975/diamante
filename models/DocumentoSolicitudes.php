<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documento_solicitudes".
 *
 * @property int $id_solicitud
 * @property string $concepto
 * @property int $produccion
 * @property int $logistica
 *
 * @property SolicitudArmadoKits[] $solicitudArmadoKits
 */
class DocumentoSolicitudes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documento_solicitudes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['produccion', 'logistica','todas','solicitud_materiales'], 'integer'],
            [['concepto'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_solicitud' => 'Id Solicitud',
            'concepto' => 'Concepto',
            'produccion' => 'Produccion',
            'logistica' => 'Logistica',
            'todas' =>'todas',
            'solicitud_materiales' => 'solicitud_materiales'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudArmadoKits()
    {
        return $this->hasMany(SolicitudArmadoKits::className(), ['id_solicitud' => 'id_solicitud']);
    }
}
