<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_producto".
 *
 * @property int $id
 * @property int $id_grupo
 * @property int $id_materia_prima
 * @property string $codigo_materia
 * @property string $nombre_materia_prima
 * @property int $id_fase
 * @property double $porcentaje_aplicacion
 * @property int $cantidad_gramos
 * @property string $codigo_homologacion
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property GrupoProducto $grupo
 * @property MateriaPrimas $materiaPrima
 * @property TipoFases $fase
 */
class ConfiguracionProducto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_producto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_grupo', 'id_materia_prima', 'id_fase', 'cantidad_gramos','id_producto'], 'integer'],
            [['porcentaje_aplicacion'], 'number'],
            [['fecha_registro'], 'safe'],
            [['codigo_materia', 'codigo_homologacion', 'user_name'], 'string', 'max' => 15],
            [['nombre_materia_prima'], 'string', 'max' => 30],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['id_materia_prima'], 'exist', 'skipOnError' => true, 'targetClass' => MateriaPrimas::className(), 'targetAttribute' => ['id_materia_prima' => 'id_materia_prima']],
            [['id_fase'], 'exist', 'skipOnError' => true, 'targetClass' => TipoFases::className(), 'targetAttribute' => ['id_fase' => 'id_fase']],
            [['id_producto'], 'exist', 'skipOnError' => true, 'targetClass' => Productos::className(), 'targetAttribute' => ['id_producto' => 'id_producto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_grupo' => 'Id Grupo',
            'id_materia_prima' => 'Id Materia Prima',
            'codigo_materia' => 'Codigo Materia',
            'nombre_materia_prima' => 'Nombre Materia Prima',
            'id_fase' => 'Id Fase',
            'porcentaje_aplicacion' => 'Porcentaje Aplicacion',
            'cantidad_gramos' => 'Cantidad Gramos',
            'codigo_homologacion' => 'Codigo Homologacion',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
            'id_producto' => 'Producto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(GrupoProducto::className(), ['id_grupo' => 'id_grupo']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductos()
    {
        return $this->hasOne(Productos::className(), ['id_producto' => 'id_producto']);
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
    public function getFase()
    {
        return $this->hasOne(TipoFases::className(), ['id_fase' => 'id_fase']);
    }
}
