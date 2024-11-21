<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use kartik\select2\Select2;
//models
$this->title = 'Listar despacho (Referencia: '.$detalle->inventario->codigo_producto.')';
$this->params['breadcrumbs'][] = ['label' => 'Listar despacho', 'url' => ['view_listar', 'id_pedido' => $id_pedido]];
$this->params['breadcrumbs'][] = $id_pedido;
$cantidad_caja = \app\models\PackingPedidoDetalle::find()->where(['=','id_packing', $modelo->id_packing])->orderBy('numero_caja ASC')->all();
?>
<div class="btn-group btn-sm" role="group">    
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view_listar', 'id_pedido' => $id_pedido], ['class' => 'btn btn-primary btn-sm']) ?>
 </div>  
<?php $form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'options' => []
            ],
        ]);
?>
<div class="panel panel-success">
    <div class="panel-heading">
        CANTIDADES A DESPACHAR
    </div>
    <div class="panel-body">
        <div class="row">
            <?= $form->field($model, 'cantidad_vendida')->textInput(['disabled' => true]) ?>
       
            <?= $form->field($model, 'cantidad_despachada')->textInput(['required' => 'true']) ?>
        </div>
        <div class="row">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="font-size: 85%;">
                        <th scope="col" style='background-color:#B9D5CE;'>Nro de caja</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Referencia</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Presentacion</th>
                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad despachada</th>
                        <th scope="col" style='background-color:#B9D5CE'></th>
                        <th scope="col" style='background-color:#B9D5CE'></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cantidad_caja as $key => $caja) {?>
                        <tr style="text-align: center; font-size: 90%;">
                            <td><?= $caja->numero_caja?></td>
                            <td><?= $caja->codigo_producto?></td>
                            <td><?= $caja->nombre_producto?></td>
                            <td><?= $caja->cantidad_despachada?></td>
                            <?php if($caja->cantidad_despachada <= 0){?>
                                <td style="width: 25px; height: 25px;">
                                    <!-- Inicio Nuevo Detalle proceso -->
                                      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ',
                                          ['/almacenamiento-producto/almacenar_producto_caja', 'id_caja' => $caja->id_detalle, 'id_pedido' => $id_pedido,'id_detalle' => $id_detalle,'sw' => $sw],
                                          [
                                              'title' => 'Almacenar unidades en caja',
                                              'data-toggle'=>'modal',
                                              'data-target'=>'#modalalmacenarunidades'.$caja->id_detalle,
                                          ])    
                                     ?>
                                  <div class="modal remote fade" id="modalalmacenarunidades<?= $caja->id_detalle ?>">
                                      <div class="modal-dialog modal-lg" style ="width: 550px;">
                                          <div class="modal-content"></div>
                                      </div>
                                  </div>
                                </td>
                                <td style="width: 25px; height: 25px;"></td>
                            <?php }else{ ?>
                                    <td style="width: 25px; height: 25px;">
                                    <!-- Inicio Nuevo Detalle proceso -->
                                      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ',
                                          ['/almacenamiento-producto/almacenar_producto_caja', 'id_caja' => $caja->id_detalle, 'id_pedido' => $id_pedido,'id_detalle' => $id_detalle,'sw' => $sw],
                                          [
                                              'title' => 'Almacenar unidades en caja',
                                              'data-toggle'=>'modal',
                                              'data-target'=>'#modalalmacenarunidades'.$caja->id_detalle,
                                          ])    
                                     ?>
                                    <div class="modal remote fade" id="modalalmacenarunidades<?= $caja->id_detalle ?>">
                                      <div class="modal-dialog modal-lg" style ="width: 550px;">
                                          <div class="modal-content"></div>
                                      </div>
                                    </div> 
                                    <td style="width: 25px; height: 25px;">
                                        <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span>', ['duplicar_caja_packing', 'id' => $modelo->id_packing, 'id_pedido' => $id_pedido,'id_detalle' => $id_detalle,'sw' => $sw, 'numero_caja' => $caja->numero_caja],['class' => 'btn btn-warning btn-xs',
                                          'data' => ['confirm' => 'Esta seguro de duplicar esta caja para seguir con el packing.', 'method' => 'post']]);?>
                                    </td>  
                            <?php } ?>        
                           
                        </tr>
                    <?php }?>
                </tbody>    
            </table>    
        </div>    
    </div>
</div>    
        
<div class="panel panel-success">
     <div class="panel-heading">
         LUGARES DE ALMACENAMIENTO
     </div>
     <div class="panel-body">
         <table class="table table-bordered table-hover">
             <thead>
                 <tr style="font-size: 85%;">
                     <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Presentacion</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Piso</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Rack</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Posicion</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Lote</th>
                      <th scope="col" style='background-color:#B9D5CE;'>F. Vcto</th>
                     <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                     <th scope="col" style='background-color:#B9D5CE;'></th>
                 </tr>
             </thead>
             <?php

             if(count($almacenamiento) > 0){
                 foreach ($almacenamiento as $val): ?>
                     <tr style="font-size: 85%;">
                         <td> <?= $val->codigo_producto ?></td>
                         <td> <?= $val->producto ?></td>
                         <td> <?= $val->piso->descripcion ?></td>
                         <td> <?= $val->rack->descripcion ?></td>
                         <td> <?= $val->posicion->posicion ?></td>
                         <td> <?= $val->numero_lote ?></td>
                         <td style="background-color: #f8efc0"> <?= $val->fecha_vencimiento ?></td>
                         <td style="text-align: right"> <?= ''.number_format($val->cantidad,0) ?></td>
                         <td style= 'width: 20px;'><input type="checkbox" name="seleccione_item[]" value="<?= $val->id ?>"></td> 
                     </tr>
                 <?php endforeach; 
             }else{
                     Yii::$app->getSession()->setFlash('info', 'Este producto NO presenta almacenamiento en los diferentes RACKS de la empresa. Contactar al administrador.');
             }?>    
         </table> 
         <div class="panel-footer text-right">
             <?= Html::submitButton("<span class='glyphicon glyphicon-send'></span> Enviar", ["class" => "btn btn-success btn-sm", 'name' => 'cantidaddespachada']) ?>     
         </div>    
     </div>
 </div>
  
</div>
<?php $form->end() ?>

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
</script>