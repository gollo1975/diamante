<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_documento".
 *
 * @property int $id_tipo_documento
 * @property string $tipo_documento
 * @property string $documento
 * @property int $proceso_nomina
 * @property int $proceso_cliente
 * @property int $proceso_proveedor
 * @property string $codigo_interfaz
 * @property string $fecha_registro
 *
 * @property Proveedor[] $proveedors
 */
class TipoDocumento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_documento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_documento', 'documento'], 'required'],
            [['proceso_nomina', 'proceso_cliente', 'proceso_proveedor'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['tipo_documento', 'codigo_interfaz'], 'string', 'max' => 4],
            [['documento'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_documento' => 'Id Tipo Documento',
            'tipo_documento' => 'Tipo Documento',
            'documento' => 'Documento',
            'proceso_nomina' => 'Proceso Nomina',
            'proceso_cliente' => 'Proceso Cliente',
            'proceso_proveedor' => 'Proceso Proveedor',
            'codigo_interfaz' => 'Codigo Interfaz',
            'fecha_registro' => 'Fecha Registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProveedors()
    {
        return $this->hasMany(Proveedor::className(), ['id_tipo_documento' => 'id_tipo_documento']);
    }
}
