<?php

//modelos
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
/* @var $model app\models\Ordenproduccion */

$this->title = 'Detalle del pedido';
$this->params['breadcrumbs'][] = ['label' => 'Pedido cliente', 'url' => ['anular_pedido']];
$this->params['breadcrumbs'][] = $model->id_pedido;
?>
<div class="pedidos-view_anulado">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>    
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['anular_pedidos'], ['class' => 'btn btn-primary btn-sm']); 
            if($model->pedido_anulado == 0 ){ ?>         
            <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Anular pedido', ['anular_pedido_total', 'id' => $model->id_pedido, 'pedido_virtual' => $pedido_virtual],['class' => 'btn btn-danger btn-sm',
                           'data' => ['confirm' => 'Esta seguro de anular este pedido para el cliente  '. $model->cliente.'.', 'method' => 'post']]);?>
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Actualizar saldos', ['actualizar_saldos', 'id' => $model->id_pedido, 'pedido_virtual' => $pedido_virtual],['class' => 'btn btn-success btn-sm',
                           'data' => ['confirm' => 'Esta seguro de actualizar los saldos de pedido del cliente  '. $model->cliente.'.', 'method' => 'post']]);
            } ?>
    </p>    
    <div class="panel panel-success">
      
        <?php $form = ActiveForm::begin([
       'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
       'fieldConfig' => [
           'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
           'labelOptions' => ['class' => 'col-sm-3 control-label'],
           'options' => []
       ],
       ]);?>
    <!-- comienza los tabs -->
        <div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#presupuestocomercial" aria-controls="presupuestocomercial" role="tab" data-toggle="tab">Presupuesto comercial <span class="badge"><?= count($pedido_presupuesto) ?></span></a></li>
                <li role="presentation"><a href="#detallepedido" aria-controls="detallepedido" role="tab" data-toggle="tab">Detalle pedido <span class="badge"><?= count($detalle_pedido) ?></span></a></li>
            </ul>
                <div class="tab-content">
                     <div role="tabpanel" class="tab-pane active" id="presupuestocomercial">
                        <div class="table-responsive">
                            <div class="panel panel-success">
                                <div class="panel-body">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr style="font-size: 90%;">
                                                <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                                <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre del producto</th>                        
                                                <th scope="col" align="center" style='background-color:#B9D5CE;'>Tipo presupuesto</th>
                                                 <th scope="col" align="center" style='background-color:#B9D5CE;'>Stock</th>    
                                                <th scope="col" align="center" style='background-color:#B9D5CE;'>Cant.</th>       
                                                 <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. Unitario</th>  
                                                <th scope="col" align="center" style='background-color:#B9D5CE;'>Subtotal</th>                        
                                                <th scope="col" align="center" style='background-color:#B9D5CE;'>Impuesto</th>  
                                                <th scope="col" align="center" style='background-color:#B9D5CE;'>Total</th> 
                                                <th scope="col" align="center" style='background-color:#B9D5CE;'><span title="Registro eliminado">R. eliminado</span></th> 
                                                 <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"></th>
                                            </tr>
                                        </thead>
                                        <body>
                                             <?php
                                             foreach ($pedido_presupuesto as $val):?>
                                                <tr style="font-size: 90%;">
                                                    <td><?= $val->inventario->codigo_producto ?></td>
                                                    <td><?= $val->inventario->nombre_producto ?></td>
                                                    <td><?= $val->presupuesto->descripcion ?></td>
                                                    <td style="background-color:#CBAAE3; color: black"><?= $val->inventario->stock_unidades ?></td>
                                                    <?php if($val->cantidad == 0){?>
                                                          <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidades[]" value="<?= $val->cantidad?>" style="text-align: right" size="7" maxlength="true"> </td> 
                                                    <?php }else{?>
                                                          <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                    <?php }?>      
                                                    <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                    <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                    <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                                    <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                                    <?php if($val->registro_eliminado == 0){?>
                                                        <td><?= $val->registroEliminado ?></td>
                                                        <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="detalle_presupuesto[]" value="<?= $val->id_detalle ?>"></td> 
                                                    <?php }else{?>
                                                        <td><?= $val->registroEliminado ?></td>
                                                          <td></td>
                                                    <?php }?>    
                                                </tr>
                                             <?php endforeach;?>          
                                        </body>
                                    </table>
                                </div>
                                 <div class="panel-footer text-right">
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-trash'></span> Eliminar presupuesto", ["class" => "btn btn-default btn-sm", 'name' => 'eliminar_presupuesto']) ?>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <!--TERMINA TBAS-->
                      <div role="tabpanel" class="tab-pane" id="detallepedido">
                        <div class="table-responsive">
                            <div class="panel panel-success">
                              <div class="panel-body">
                                    <table class="table table-bordered table-hover">
                                       <thead>
                                           <tr style="font-size: 90%;">
                                                <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                                <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                                               <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th>
                                               <th scope="col" style='background-color:#B9D5CE;'>Vr. unit.</th>
                                                <th scope="col" style='background-color:#B9D5CE;'>Subtotal.</th>
                                               <th scope="col" style='background-color:#B9D5CE;'>Impuesto</th>
                                               <th scope="col" style='background-color:#B9D5CE;'>Total</th>
                                               <th scope="col" align="center" style='background-color:#B9D5CE;'><span title="Registro eliminado">R. eliminado</span></th> 
                                                 <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcarDos(this);"/></th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                       <?php
                                     
                                       foreach ($detalle_pedido as $val):                          
                                           ?>
                                       <tr style="font-size: 90%;">
                                           <td><?= $val->inventario->nombre_producto ?></td>
                                           <td style="background-color:#CBAAE3; color: black"><?= $val->inventario->stock_unidades ?></td>
                                           <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                           <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                           <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                           <td style="text-align: right"><?= ''.number_format($val->impuesto,0) ?></td>
                                           <td style="text-align: right"><?= ''.number_format($val->total_linea,0) ?></td>
                                           <?php if($val->registro_eliminado == 0){?>
                                                <td><?= $val->registroEliminado ?></td>
                                                <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="detalle_pedido[]" value="<?= $val->id_detalle ?>"></td> 
                                            <?php }else{?>
                                                <td><?= $val->registroEliminado ?></td>
                                                <td></td>
                                            <?php }?>    
                                           
                                       </tr>
                                       </tbody>
                                       <?php endforeach; ?>
                                   </table>
                               </div>
                                <div class="panel-footer text-right">
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-trash'></span> Eliminar pedido", ["class" => "btn btn-default btn-sm", 'name' => 'eliminar_pedido']) ?>
                                </div>
                           </div>
                    </div>
                </div>   
                    <!--TERMINA TABS-->

                </div>  
        </div>
    </div>  
     <?php ActiveForm::end(); ?>  
</div>
<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
        function marcarDos(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>

   