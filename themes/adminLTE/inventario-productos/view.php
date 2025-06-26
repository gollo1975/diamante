<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'DETALLE DE INVENTARIO';
$this->params['breadcrumbs'][] = ['label' => 'Inventario producto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_inventario;
$view = 'inventario-productos';
?>
<div class="operarios-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{ ?>
                <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_inventario'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 8, 'codigo' => $model->id_inventario,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            INVENTARIO PRODUCTO TERMINADO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_inventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'costo_unitario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->costo_unitario,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_vencimiento') ?></th>
                    <td><?= Html::encode($model->fecha_vencimiento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo') ?></th>
                    <td><?= Html::encode($model->grupo->nombre_grupo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'valor_iva') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->valor_iva,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_iva') ?></th>
                    <td><?= Html::encode($model->aplicaIva) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_proveedor') ?></th>
                    <td><?= Html::encode($model->proveedor->nombre_completo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'subtotal') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->subtotal,0)) ?></td>
                </tr>
               
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'inventario_inicial') ?></th>
                    <td><?= Html::encode($model->inventarioInicial) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_inventario') ?></th>
                    <td><?= Html::encode($model->aplicaInventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'porcentaje_iva') ?></th>
                    <td><?= Html::encode($model->porcentaje_iva) ?></td>
                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_inventario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_inventario,0)) ?></td>
                </tr>
                 <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_ean') ?></th>
                    <td><?= Html::encode($model->codigo_ean) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'unidades_entradas') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->unidades_entradas,0)) ?></td>
                   <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'stock_unidades') ?></th>
                    <td style="text-align: right; background-color:#F5EEF8;"><?= Html::encode(''.number_format($model->stock_unidades,0)) ?></td>
                    
                </tr>
                <tr style="font-size: 85%;">
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_presupuesto') ?></th>
                    <td><?= Html::encode($model->aplicaPresupuesto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'activar_producto_venta') ?></th>
                    <td><?= Html::encode($model->activarProducto) ?></td>
                   
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion_producto') ?></th>
                    <td colspan="5"><?= Html::encode($model->descripcion_producto)?></td>
                </tr>
                
                
            </table>
        </div>
    </div>
    <!--INICIO LOS TABS-->
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#ordenproduccion" aria-controls="ordenproduccion" role="tab" data-toggle="tab">Lotes de producción <span class="badge"><?= count($detalle_entrada) ?></span></a></li>
            <li role="presentation" ><a href="#entrada_producto" aria-controls="entrada_producto" role="tab" data-toggle="tab">Entradas productos <span class="badge"><?= count($entradas) ?></span></a></li>
            <li role="presentation" ><a href="#devolucion_producto" aria-controls="devolucion_producto" role="tab" data-toggle="tab">Devolucion de productos <span class="badge"><?= count($devoluciones) ?></span></a></li>
        </ul>
        
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="ordenproduccion">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Orden produccion</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Codigo</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Nombre producto</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Lote</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha_vencimiento</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detalle_entrada as $detalles):?>
                                       
                                            <tr style="font-size: 85%;">
                                                <td><?= $detalles->id_detalle ?></td>  
                                                <td><?= $detalles->ordenProduccion->numero_orden ?></td>
                                                <td><?= $detalles->codigo_producto ?></td>
                                                <td><?= $detalles->descripcion ?></td>
                                                <td style="text-align: right"><?= $detalles->cantidad ?></td>
                                                <td><?= $detalles->numero_lote ?></td>
                                                <td><?= $detalles->fecha_vencimiento ?></td>
                                                <td style= 'width: 20px; height: 20px;'>
                                                    <?php if($token == 1){?>
                                                        <a href="<?= Url::toRoute(["/orden-produccion/imprimirordenproduccion",'id'=>$val->id_orden_produccion]) ?>" ><span class="glyphicon glyphicon-print" title="Imprimir orden de produccion "></span></a>
                                                    <?php }?>
                                                </td>        
                                            </tr>
                                     <?php endforeach;?>
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
                <?= LinkPager::widget(['pagination' => $pagination]) ?>
            </div>
            <!--FIN TABS-->
            <!--INICIO EL OTRO TABS -->
             <div role="tabpanel" class="tab-pane " id="entrada_producto">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>No entrada</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>T. Entrada</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Proveedor</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Soporte</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Lote</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>F. entrada</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cant.</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Vr. Unit.</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Subtotal</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Total </th>
                                        <th scope="col" style='background-color:#B9D5CE;'>User name </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($entradas as $val): ?>
                                            <tr style ='font-size: 85%;'>                
                                                <td><?= $val->id_entrada?></td>
                                                <td><?= $val->entrada->tipoEntrada?></td>    
                                                <td><?= $val->entrada->proveedor->nombre_completo?></td>
                                                <td><?= $val->entrada->numero_soporte?></td>
                                                <td><?= $val->numero_lote?></td>
                                                <td><?= $val->entrada->fecha_proceso?></td>
                                                <td style="text-align: right;"><?= ''.number_format($val->cantidad,0)?></td>
                                                <td style="text-align: right;"><?= ''.number_format($val->valor_unitario,0)?></td>
                                                <td style="text-align: right;"><?= ''.number_format($val->subtotal,0    )?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->total_iva,0)?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->total_entrada,0)?></td>
                                                <td><?= $val->entrada->user_name_crear?></td>
                                            </tr>            
                                        <?php endforeach;?>
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            <!-- FIN TABS-->
            <div role="tabpanel" class="tab-pane " id="devolucion_producto">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:85%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>No devolución</th>
                                         <th scope="col" style='background-color:#B9D5CE;'>Tipo devolución</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>F. devolución</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Nota credito</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>F. nota credito</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cliente</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>T. Inventario</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>T. Averias</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($devoluciones as $val): ?>
                                            <tr style ='font-size: 85%;'>                
                                                <td><?= $val->devolucion->numero_devolucion?></td>
                                                <td><?= $val->tipoDevolucion->concepto?></td>    
                                                <td><?= $val->devolucion->fecha_devolucion?></td>
                                                <td><?= $val->devolucion->nota->numero_nota_credito?></td>
                                                <td><?= $val->devolucion->nota->fecha_nota_credito?></td>
                                                <td><?= $val->devolucion->cliente->nombre_completo?></td>
                                                <td style="text-align: right;"><?= ''.number_format($val->cantidad_devolver,0)?></td>
                                                <td style="text-align: right;"><?= ''.number_format($val->cantidad_averias,0)?></td>
                                            </tr>            
                                        <?php endforeach;?>
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </div>    
</div>

