<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orden_ensamble_producto_empaque".
 *
 * @property int $id
 * @property int $id_ensamble
 * @property int $id_materia_prima
 * @property int $unidades_solicitadas
 * @property int $unidades_devolucion
 * @property int $unidades_averias
 * @property int $unidades_utilizadas
 * @property int $unidades_sala_tecnica
 * @property int $unidades_muestra_retencion
 * @property int $unidades_reales
 * @property string $fecha_hora_carga
 * @property string $user_name
 *
 * @property OrdenEnsambleProducto $ensamble
 * @property MateriaPrimas $materiaPrima
 */
class OrdenEnsambleProductoEmpaque extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orden_ensamble_producto_empaque';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ensamble', 'id_materia_prima', 'unidades_solicitadas', 'unidades_devolucion', 'unidades_averias', 'unidades_utilizadas', 'unidades_sala_tecnica',
                'unidades_muestra_retencion', 'unidades_reales','stock','importado','id_presentacion','linea_exportada_inventario'], 'integer'],
            [['fecha_hora_carga'], 'safe'],
            [['user_name','alerta'], 'string', 'max' => 15],
            [['id_ensamble'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenEnsambleProducto::className(), 'targetAttribute' => ['id_ensamble' => 'id_ensamble']],
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
            'id' => 'ID',
            'id_ensamble' => 'Id Ensamble',
            'id_materia_prima' => 'Id Materia Prima',
            'unidades_solicitadas' => 'Unidades Solicitadas',
            'unidades_devolucion' => 'Unidades Devolucion',
            'unidades_averias' => 'Unidades Averias',
            'unidades_utilizadas' => 'Unidades Utilizadas',
            'unidades_sala_tecnica' => 'Unidades Sala Tecnica',
            'unidades_muestra_retencion' => 'Unidades Muestra Retencion',
            'unidades_reales' => 'Unidades Reales',
            'fecha_hora_carga' => 'Fecha Hora Carga',
            'user_name' => 'User Name',
            'stock' => 'stock',
            'alerta' => 'alerta',
            'importado' => 'Importado',
            'id_presentacion' => 'id_presentacion',
            'linea_exportada_inventario' => 'linea_exportada_inventario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnsamble()
    {
        return $this->hasOne(OrdenEnsambleProducto::className(), ['id_ensamble' => 'id_ensamble']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresentacion()
    {
        return $this->hasOne(PresentacionProducto::className(), ['id_presentacion' => 'id_presentacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateriaPrima()
    {
        return $this->hasOne(MateriaPrimas::className(), ['id_materia_prima' => 'id_materia_prima']);
    }
    
    public function getImportadoRegistro() {
        if($this->importado == 0){
            $importadoregistro ='NO';
        }else{
            $importadoregistro = 'SI';
        }
        return $importadoregistro;
    }
}
