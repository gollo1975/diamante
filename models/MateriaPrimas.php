<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "materia_primas".
 *
 * @property int $id_materia_prima
 * @property string $codigo_materia_prima
 * @property string $descripcion
 * @property int $id_medida
 * @property double $valor_unidad
 * @property int $aplica_iva
 * @property double $porcentaje_iva
 * @property int $valor_iva
 * @property int $total_cantidad
 * @property int $total_materia_prima
 * @property string $fecha_entrada
 * @property string $fecha_vencimiento
 * @property string $fecha_registro
 * @property string $usuario_creador
 * @property string $usuario_editado
 * @property int $aplica_inventario
 * @property int $entrada_salida
 * @property string $codigo_ean
 *
 * @property MedidaMateriaPrima $medida
 */
class MateriaPrimas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'materia_primas';
    }
   
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->materia_prima = strtoupper($this->materia_prima); 
 
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_materia_prima', 'materia_prima', 'id_medida', 'aplica_inventario','valor_unidad'], 'required'],
            [['id_medida', 'aplica_iva', 'valor_iva', 'total_cantidad', 'total_materia_prima', 'aplica_inventario','inventario_inicial',
                'subtotal','id_solicitud','stock_gramos','convertir_gramos','unidades_requeridas','salida_materia_prima'], 'integer'],
            [['valor_unidad', 'porcentaje_iva','stock'], 'number'],
            [['fecha_entrada', 'fecha_registro'], 'safe'],
            [['codigo_materia_prima', 'usuario_creador', 'usuario_editado'], 'string', 'max' => 15],
            [['descripcion'], 'string', 'max' => 100],
            [['materia_prima'], 'string', 'max' => 40],
            [['codigo_ean'], 'string', 'max' => 15],
            [['id_medida'], 'exist', 'skipOnError' => true, 'targetClass' => MedidaMateriaPrima::className(), 'targetAttribute' => ['id_medida' => 'id_medida']],
            [['id_solicitud'], 'exist', 'skipOnError' => true, 'targetClass' => TipoSolicitud::className(), 'targetAttribute' => ['id_solicitud' => 'id_solicitud']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_materia_prima' => 'Id:',
            'codigo_materia_prima' => 'Código:',
            'descripcion' => 'Descripcion:',
            'id_medida' => 'Medida:',
            'valor_unidad' => 'Valor unidad:',
            'aplica_iva' => 'Aplica Iva:',
            'porcentaje_iva' => 'Porcentaje Iva:',
            'valor_iva' => 'Valor Iva:',
            'total_cantidad' => 'Cantidad:',
            'total_materia_prima' => 'Valor total:',
            'fecha_entrada' => 'Fecha entrada:',
            'fecha_registro' => 'Fecha Registro:',
            'usuario_creador' => 'User name creador',
            'usuario_editado' => 'User name editado',
            'aplica_inventario' => 'Aplica inventario:',
            'codigo_ean' => 'Codigo Ean',
            'materia_prima' => 'Materia prima:',
            'stock' => 'Stock:',
            'inventario_inicial' => 'Inventario inicial:',
            'subtotal' => 'Subtotal:',
            'id_solicitud' => 'Clasificacion:',
            'stock_gramos' => 'Stock gramos:',
            'convertir_gramos' => 'Convertir a gramos:',
            'salida_materia_prima' => 'Salida de materia prima:'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedida()
    {
        return $this->hasOne(MedidaMateriaPrima::className(), ['id_medida' => 'id_medida']);
    }
    public function getTipoSolicitud()
    {
        return $this->hasOne(TipoSolicitud::className(), ['id_solicitud' => 'id_solicitud']);
    }
    
    //proceso que llama los datos concatenados
    
    public function getMateriasPrimas()
    {
        return "{$this->materia_prima} - {$this->codigo_materia_prima}";
    }
    
    public function getAplicaInventario() {
        if($this->aplica_inventario == 1){
            $aplicainventario = 'SI';
        }else{
            $aplicainventario = 'NO';
        }
        return $aplicainventario;
    }
    
      public function getAplicaIva() {
        if($this->aplica_iva == 1){
            $aplicaiva = 'SI';
        }else{
            $aplicaiva = 'NO';
        }
        return $aplicaiva;
    }
    
    public function getInventarioInicial() {
        if($this->inventario_inicial == 0){
            $inventarioinicial = 'NO';
        }else{
            $inventarioinicial = 'SI';
        }
        return $inventarioinicial;
    }
     public function getCovertirGramos() {
        if($this->convertir_gramos == 0){
            $convertirgramos = 'NO';
        }else{
            $convertirgramos = 'SI';
        }
        return $convertirgramos;
    }
}
