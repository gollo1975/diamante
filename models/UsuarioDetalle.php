<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario_detalle".
 *
 * @property int $codusuario_detalle
 * @property int $codusuario
 * @property int $id_permiso
 * @property int $activo
 *
 * @property Users $codusuario
 * @property Permisos $permiso
 */
class UsuarioDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codusuario', 'id_permiso'], 'required'],
            [['codusuario', 'id_permiso', 'activo'], 'integer'],
            [['codusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['codusuario' => 'codusuario']],
            [['id_permiso'], 'exist', 'skipOnError' => true, 'targetClass' => Permisos::className(), 'targetAttribute' => ['id_permiso' => 'id_permiso']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codusuario_detalle' => 'Codusuario Detalle',
            'codusuario' => 'Codusuario',
            'id_permiso' => 'Id Permiso',
            'activo' => 'Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodusuario()
    {
        return $this->hasOne(Users::className(), ['codusuario' => 'codusuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermiso()
    {
        return $this->hasOne(Permisos::className(), ['id_permiso' => 'id_permiso']);
    }
}
