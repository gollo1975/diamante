<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;                       
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\CierreCaja */

$this->title = 'DETALLE CIERRE DE CAJA';
$this->params['breadcrumbs'][] = ['label' => 'Cierre Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cierre-caja-view">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <p>
        <?php echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']);
        if ($model->autorizado == 0 && $model->numero_cierre == 0){
            echo  Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_cierre, 'accesoToken' => $accesoToken], ['class' => 'btn btn-default btn-sm']); 
        }else{
            if($model->autorizado == 1 && $model->numero_cierre == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $id, 'accesoToken' => $accesoToken], ['class' => 'btn btn-default btn-sm']);
                echo Html::a('<span class="glyphicon glyphicon-book"></span> Cerrar caja', ['cerrar_caja_punto', 'id' => $id, 'accesoToken' => $accesoToken],['class' => 'btn btn-default btn-sm',
                           'data' => ['confirm' => 'Esta seguro de cerrar la caja del punto de venta ('.$model->punto->nombre_punto.').', 'method' => 'post']]);
             }
        }?>    
    </p>    
    <div class="panel panel-success">
        <div class="panel-heading">
           CIERRE DE CAJA
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_cierre) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_punto') ?></th>
                    <td><?= Html::encode($model->punto->nombre_punto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_corte') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_factura') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_factura,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_cierre') ?></th>
                    <td><?= Html::encode($model->numero_cierre) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_hora_registro') ?></th>
                    <td><?= Html::encode($model->fecha_hora_registro) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'proceso_cerrado') ?></th>
                    <td><?= Html::encode($model->procesoCerrado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_remision') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_remision,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                   
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Efectivo_factura') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_efectivo_factura,0)) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Efectivo_remision') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_efectivo_remision,0)) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Transacion_factura') ?></th>
                   <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_transacion_factura,0)) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Transacion_remision') ?></th>
                   <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_transacion_remision,0)) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_cierre_caja') ?></th>
                   <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_cierre_caja,0)) ?></td>
                    
                </tr>
            </table>
        </div>
    </div>  
    <?php $form = ActiveForm::begin([
      'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
      'fieldConfig' => [
          'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
          'labelOptions' => ['class' => 'col-sm-3 control-label'],
          'options' => []
      ],
      ]);?>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listadofacturas" aria-controls="listadofacturas" role="tab" data-toggle="tab">Recibo facturas <span class="badge"><?= count($conrecibofactura) ?></span></a></li>
             <li role="presentation"><a href="#listadoremision" aria-controls="listadoremision" role="tab" data-toggle="tab">Recibo remision <span class="badge"><?= count($conreciboremision) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="listadofacturas">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Factura</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cliente</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Banco</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'>Forma pago</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'>No transacion</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Valor pago</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($conrecibofactura as $val):?>
                                        <tr style='font-size:90%;'> 
                                            <td><?= $val->id_detalle?></td>
                                            <td><?= $val->factura->numero_factura?></td>
                                            <td><?= $val->factura->clienteFactura->nombre_completo?></td>
                                            <td><?= $val->recibo->codigoBanco->entidad_bancaria?></td>
                                            <td><?= $val->recibo->formaPago->concepto?></td>
                                            <td><?= $val->recibo->numero_transacion?></td>
                                            <td style="text-align: right"><?= ''. number_format($val->valor_pago,0)?></td>
                                            <td><input type="checkbox" name="listado_eliminar[]" value="<?= $val->id_detalle ?>"></td> 
                                        </tr>
                                    <?php
                                    endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer text-right">
                            <?php
                            if($model->autorizado == 0){ 
                                if(count($conrecibofactura) == null){
                                    echo  Html::a('<span class="glyphicon glyphicon-import"></span> Importar recibo factura', ['cargar_recibo_factura', 'id' => $model->id_cierre, 'accesoToken' => $accesoToken, 'fecha_inicio' => $model->fecha_inicio], ['class' => 'btn btn-success btn-sm']);
                                }else{
                                   echo Html::submitButton("<span class='glyphicon glyphicon-trash'></span> Eliminar todo", ["class" => "btn btn-danger btn-sm", 'name' => 'eliminartodo']);
                                }
                            }    
?> 
                        </div>
                    </div>
                </div>
            </div>
            <!--TERMINA TABS-->
            <div role="tabpanel" class="tab-pane" id="listadoremision">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Remision</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cliente</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Banco</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'>Forma pago</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'>No transacion</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Valor pago</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcaremision(this);"/></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($conreciboremision as $val):?>
                                        <tr style='font-size:90%;'> 
                                            <td><?= $val->id_detalle?></td>
                                            <td><?= $val->remision->numero_remision?></td>
                                            <td><?= $val->remision->cliente->nombre_completo?></td>
                                            <td><?= $val->recibo->codigoBanco->entidad_bancaria?></td>
                                            <td><?= $val->recibo->formaPago->concepto?></td>
                                            <td><?= $val->recibo->numero_transacion?></td>
                                            <td style="text-align: right"><?= ''. number_format($val->valor_pago,0)?></td>
                                            <td><input type="checkbox" name="listado_eliminar_remision[]" value="<?= $val->id_detalle ?>"></td> 
                                        </tr>
                                    <?php
                                    endforeach;?>
                                </tbody>        
                            </table>
                        </div>
                         <div class="panel-footer text-right">
                            <?php
                            if($model->autorizado == 0){ 
                                if(count($conreciboremision) == null){
                                    echo  Html::a('<span class="glyphicon glyphicon-import"></span> Importar recibo remision', ['cargar_recibo_remision', 'id' => $model->id_cierre, 'accesoToken' => $accesoToken, 'fecha_inicio' => $model->fecha_inicio], ['class' => 'btn btn-info btn-sm']);
                                }else{
                                   echo Html::submitButton("<span class='glyphicon glyphicon-trash'></span> Eliminar todo", ["class" => "btn btn-danger btn-sm", 'name' => 'eliminar_todo_remision']);
                                }
                            }    
?> 
                        </div>
                    </div>
                </div>
            </div>
        <!--TERMINA TABS-->
        </div>
    </div>    
    <?php $form->end() ?>     
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
        //proceso de remision
        function marcaremision(source) 
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
