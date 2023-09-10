<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permisos".
 *
 * @property int $id_permiso
 * @property string $permiso
 * @property string $menu_operacion
 *
 * @property UsuarioDetalle[] $usuarioDetalles
 */
class Permisos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permisos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permiso', 'menu_operacion'], 'required'],
            [['permiso', 'menu_operacion'], 'string', 'max' => 50],
            ['tipo_modulo','integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_permiso' => 'Id Permiso',
            'permiso' => 'Permiso',
            'menu_operacion' => 'Menu Operacion',
            'tipo_modulo' => 'Tipo modulo:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioDetalles()
    {
        return $this->hasMany(UsuarioDetalle::className(), ['id_permiso' => 'id_permiso']);
    }
}
