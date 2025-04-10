<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_produccion_fase_inicial".
 *
 * @property int $id_detalle
 * @property int $id_orden_produccion
 * @property int $id
 * @property int $id_materia_prima
 * @property int $id_grupo
 * @property int $id_fase
 * @property double $porcentaje_aplicacion
 * @property int $cantidad_gramos
 * @property string $codigo_homologacion
 * @property string $user_name
 * @property int $fecha_registro
 * @property int $importado
 *
 * @property OrdenProduccion $ordenProduccion
 * @property ConfiguracionProducto $id0
 * @property MateriaPrimas $materiaPrima
 * @property GrupoProducto $grupo
 * @property TipoFases $fase
 */
class OrdenProduccionFaseInicial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_produccion_fase_inicial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_orden_produccion', 'id', 'id_materia_prima', 'id_grupo', 'id_fase', 'cantidad_gramos', 'fecha_registro', 'importado','cantidad_faltante','id_producto'], 'integer'],
            [['porcentaje_aplicacion'], 'number'],
            [['codigo_homologacion', 'user_name'], 'string', 'max' => 15],
            [['cumple_existencia'], 'string', 'max' => 5],
            [['id_orden_produccion'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenProduccion::className(), 'targetAttribute' => ['id_orden_produccion' => 'id_orden_produccion']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionProducto::className(), 'targetAttribute' => ['id' => 'id']],
            [['id_materia_prima'], 'exist', 'skipOnError' => true, 'targetClass' => MateriaPrimas::className(), 'targetAttribute' => ['id_materia_prima' => 'id_materia_prima']],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
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
            'id_detalle' => 'Id Detalle',
            'id_orden_produccion' => 'Id Orden Produccion',
            'id' => 'ID',
            'id_materia_prima' => 'Id Materia Prima',
            'id_grupo' => 'Id Grupo',
            'id_producto' => 'Productos',
            'id_fase' => 'Id Fase',
            'porcentaje_aplicacion' => 'Porcentaje Aplicacion',
            'cantidad_gramos' => 'Cantidad Gramos',
            'codigo_homologacion' => 'Codigo Homologacion',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
            'importado' => 'Importado',
            'cumple_existencia' => 'Cumple existencia:',
            'cantidad_faltante' => 'Cantidad faltante:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdenProduccion()
    {
        return $this->hasOne(OrdenProduccion::className(), ['id_orden_produccion' => 'id_orden_produccion']);
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
    public function getConfiguracionProducto()
    {
        return $this->hasOne(ConfiguracionProducto::className(), ['id' => 'id']);
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
    public function getGrupo()
    {
        return $this->hasOne(GrupoProducto::className(), ['id_grupo' => 'id_grupo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFase()
    {
        return $this->hasOne(TipoFases::className(), ['id_fase' => 'id_fase']);
    }
    
     public function getDocumentoExportado() {
        if($this->importado == 0){
           $documentoexportado = 'NO';
        }else{
            $documentoexportado = 'SI';
        }
        return $documentoexportado;
    }
}
