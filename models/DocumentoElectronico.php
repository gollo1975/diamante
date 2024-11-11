<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documento_electronico".
 *
 * @property int $id_documento
 * @property string $concepto
 * @property string $sigla
 * @property string $fecha_creacion
 */
class DocumentoElectronico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documento_electronico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto', 'sigla'], 'required'],
            [['fecha_creacion'], 'safe'],
            [['concepto'], 'string', 'max' => 40],
            [['sigla'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_documento' => 'codigo documento',
            'concepto' => 'Concepto',
            'sigla' => 'Sigla',
            'fecha_creacion' => 'Fecha Creacion',
        ];
    }
}
