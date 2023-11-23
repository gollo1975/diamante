<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documento_almacenamiento".
 *
 * @property int $id_documento
 * @property string $concepto
 */
class DocumentoAlmacenamiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documento_almacenamiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['concepto'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_documento' => 'Id Documento',
            'concepto' => 'Concepto',
        ];
    }
}
