<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pedido_presupuesto_comercial".
 *
 * @property int $id_detalle
 * @property int $id_inventario
 * @property int $id_pedido
 * @property int $id_presupuesto
 * @property int $cantidad
 * @property int $valor_unitario
 * @property int $subtotal
 * @property int $impuesto
 * @property int $total_linea
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property InventarioProductos $inventario
 * @property Pedidos $pedido
 * @property PresupuestoEmpresarial $presupuesto
 */
class PedidoPresupuestoComercial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedido_presupuesto_comercial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario', 'id_pedido', 'id_presupuesto', 'cantidad', 'valor_unitario', 'subtotal', 'impuesto', 'total_linea','registro_eliminado',
                'cantidad_despachada','historico_cantidad_vendida','linea_validada','regenerar_linea','cargar_existencias','consultado','cantidad_faltante'], 'integer'],
            [['fecha_registro','fecha_alistamiento'], 'safe'],
            [['user_name','numero_lote'], 'string'],
            [['id_inventario'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioProductos::className(), 'targetAttribute' => ['id_inventario' => 'id_inventario']],
            [['id_pedido'], 'exist', 'skipOnError' => true, 'targetClass' => Pedidos::className(), 'targetAttribute' => ['id_pedido' => 'id_pedido']],
            [['id_presupuesto'], 'exist', 'skipOnError' => true, 'targetClass' => PresupuestoEmpresarial::className(), 'targetAttribute' => ['id_presupuesto' => 'id_presupuesto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detalle' => 'Id Detalle',
            'id_inventario' => 'Id Inventario',
            'id_pedido' => 'Id Pedido',
            'id_presupuesto' => 'Id Presupuesto',
            'cantidad' => 'Cantidad',
            'valor_unitario' => 'Valor Unitario',
            'subtotal' => 'Subtotal',
            'impuesto' => 'Impuesto',
            'total_linea' => 'Total Linea',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
            'registro_eliminado' => 'Registro eliminado:',
            'cantidad_despachada' => 'cantidad_despachada',
            'historico_cantidad_vendida' => 'historico_cantidad_vendida',
            'linea_validada' => 'linea_validada',
            'regenerar_linea' => 'regenerar_linea',
            'fecha_alistamiento' => 'Fecha alistamiento:',
            'numero_lote' => 'numero_lote',
            'cargar_existencias' => 'cargar_existencias',
            'consultado' => 'Consultado:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioProductos::className(), ['id_inventario' => 'id_inventario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(Pedidos::className(), ['id_pedido' => 'id_pedido']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresupuesto()
    {
        return $this->hasOne(PresupuestoEmpresarial::className(), ['id_presupuesto' => 'id_presupuesto']);
   }
   
   public function getRegistroEliminado() {
      if($this->registro_eliminado ==0){
          $registroeliminado = 'NO';
      }else{
          $registroeliminado = 'SI';
      }
      return $registroeliminado;
   }
   public function getLineaValidada() {
      if($this->linea_validada == 0){
          $lineavalidada = 'NO';
      }else{
          $lineavalidada = 'SI';
      }
      return $lineavalidada;
   }
   
   public function getRegenerarLinea() {
      if($this-> regenerar_linea == 0){
           $regenerarlinea = 'NO';
      }else{
          $regenerarlinea = 'SI';
      }
      return $regenerarlinea;
   }
}
