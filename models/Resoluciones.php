<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resoluciones".
 *
 * @property int $id_resolucion
 * @property string $resolucion
 * @property string $fecha_inicio
 * @property string $fecha_final
 * @property string $fecha_vencimiento
 * @property int $estado_resolucion
 * @property string $fecha_creacion
 * @property string $usuario_creador
 *
 * @property MatriculaEmpresa[] $matriculaEmpresas
 */
class Resoluciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resoluciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resolucion', 'fecha_inicio', 'fecha_final', 'fecha_vencimiento'], 'required'],
            [['fecha_inicio', 'fecha_final', 'fecha_vencimiento', 'fecha_creacion'], 'safe'],
            [['estado_resolucion'], 'integer'],
            [['resolucion'], 'string', 'max' => 40],
            [['usuario_creador'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_resolucion' => 'Id Resolucion',
            'resolucion' => 'Resolucion',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_final' => 'Fecha Final',
            'fecha_vencimiento' => 'Fecha Vencimiento',
            'estado_resolucion' => 'Estado Resolucion',
            'fecha_creacion' => 'Fecha Creacion',
            'usuario_creador' => 'Usuario Creador',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculaEmpresas()
    {
        return $this->hasMany(MatriculaEmpresa::className(), ['id_resolucion' => 'id_resolucion']);
    }
}
