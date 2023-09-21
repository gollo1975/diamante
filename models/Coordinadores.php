<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coordinadores".
 *
 * @property int $id_coordinador
 * @property int $id_tipo_documento
 * @property string $documento
 * @property string $nombres
 * @property string $apellidos
 * @property resource $nombre_completo
 * @property string $celular
 * @property string $email
 * @property resource $user_name
 * @property string $fecha_registro
 *
 * @property TipoDocumento $tipoDocumento
 */
class Coordinadores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coordinadores';
    }
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombres = strtoupper($this->nombres); 
        $this->apellidos = strtoupper($this->apellidos); 
        $this->email = strtolower($this->email); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tipo_documento', 'documento', 'nombres', 'apellidos', 'celular', 'email'], 'required'],
            [['id_tipo_documento'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['documento', 'user_name'], 'string', 'max' => 15],
            [['nombres', 'apellidos', 'email'], 'string', 'max' => 30],
            ['email', 'email'],
            [['nombre_completo'], 'string', 'max' => 50],
            [['celular'], 'string', 'max' => 12],
            [['id_tipo_documento'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocumento::className(), 'targetAttribute' => ['id_tipo_documento' => 'id_tipo_documento']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_coordinador' => 'Id',
            'id_tipo_documento' => 'Tipo documento',
            'documento' => 'Documento',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'nombre_completo' => 'Nombre completo',
            'celular' => 'Celular',
            'email' => 'Email',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoDocumento()
    {
        return $this->hasOne(TipoDocumento::className(), ['id_tipo_documento' => 'id_tipo_documento']);
    }
}
