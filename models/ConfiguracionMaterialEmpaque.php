<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_material_empaque".
 *
 * @property int $id_configuracion
 * @property int $id_materia_prima
 * @property string $codigo_material
 * @property string $codigo_homologacion
 * @property string $user_name
 * @property string $fecha_hora_registro
 * @property int $id_presentacion
 *
 * @property MateriaPrimas $materiaPrima
 * @property PresentacionProducto $presentacion
 */
class ConfiguracionMaterialEmpaque extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_material_empaque';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_materia_prima', 'id_presentacion'], 'integer'],
            [['fecha_hora_registro'], 'safe'],
            [['id_presentacion'], 'required'],
            [['codigo_material', 'codigo_homologacion', 'user_name'], 'string', 'max' => 15],
            [['id_materia_prima'], 'exist', 'skipOnError' => true, 'targetClass' => MateriaPrimas::className(), 'targetAttribute' => ['id_materia_prima' => 'id_materia_prima']],
            [['id_presentacion'], 'exist', 'skipOnError' => true, 'targetClass' => PresentacionProducto::className(), 'targetAttribute' => ['id_presentacion' => 'id_presentacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_configuracion' => 'Id Configuracion',
            'id_materia_prima' => 'Id Materia Prima',
            'codigo_material' => 'Codigo Material',
            'codigo_homologacion' => 'Codigo Homologacion',
            'user_name' => 'User Name',
            'fecha_hora_registro' => 'Fecha Hora Registro',
            'id_presentacion' => 'Id Presentacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateriaPrima()
    {
        return $this->hasOne(MateriaPrimas::className(), ['id_materia_prima' => 'id_materia_prima']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresentacion()
    {
        return $this->hasOne(PresentacionProducto::className(), ['id_presentacion' => 'id_presentacion']);
    }
}
