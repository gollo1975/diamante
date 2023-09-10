<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documentodir".
 *
 * @property int $iddocumentodir
 * @property int $codigodocumento
 * @property string $nombre
 *
 * @property Archivodir[] $archivodirs
 */
class Documentodir extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documentodir';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigodocumento'], 'integer'],
            [['nombre'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddocumentodir' => 'Iddocumentodir',
            'codigodocumento' => 'Codigodocumento',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArchivodirs()
    {
        return $this->hasMany(Archivodir::className(), ['iddocumentodir' => 'iddocumentodir']);
    }
}
