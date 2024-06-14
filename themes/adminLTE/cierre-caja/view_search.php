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
        <?php echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_cierre_caja'], ['class' => 'btn btn-primary btn-sm']);?>
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
                                        </tr>
                                    <?php
                                    endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer text-right">
                             <?= Html::a('<span class="glyphicon glyphicon-export"></span> Excel remisiones', ['excel_recibo_facturas', 'id' => $id], ['class' => 'btn btn-success btn-sm']) ?>
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
                                            
                                        </tr>
                                    <?php
                                    endforeach;?>
                                </tbody>        
                            </table>
                        </div>
                         <div class="panel-footer text-right">
                             <?= Html::a('<span class="glyphicon glyphicon-export"></span> Excel remisiones', ['excel_recibo_remision', 'id' => $id], ['class' => 'btn btn-success btn-sm']) ?>
                        </div>
                    </div>
                </div>
            </div>
        <!--TERMINA TABS-->
        </div>
    </div>    
    <?php $form->end() ?>     
</div>

