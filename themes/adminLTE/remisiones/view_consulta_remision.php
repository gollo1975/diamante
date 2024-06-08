<?php

//modelos

//clase
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

$this->title = 'DETALLE DE LA REMISION';
$this->params['breadcrumbs'][] = ['label' => 'Remisiones', 'url' => ['search_producto_vendido']];
$this->params['breadcrumbs'][] = $model->id_remision;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<div class="factura-venta-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?php 
       
        echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_producto_vendido'], ['class' => 'btn btn-primary btn-sm']);
        echo  Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_remision_venta', 'id' => $model->id_remision], ['class' => 'btn btn-default btn-sm']);?>            
       
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
           FACTURA DE VENTA
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style="font-size: 90%;">
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_remision") ?></th>
                <td><?= Html::encode($model->id_remision) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Nit_Cedula') ?>:</th>
                <td><?= Html::encode($model->cliente->nit_cedula) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Cliente') ?>:</th>
                <td><?= Html::encode($model->cliente->nombre_completo) ?></td>
                 <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Direccion') ?>:</th>
                 <td><?= Html::encode($model->cliente->direccion) ?></td>
            </tr>
            <tr style="font-size: 90%;">
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "numero_remision") ?></th>
                <td><?= Html::encode($model->numero_remision) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                <td><?= Html::encode($model->fecha_inicio) ?></td>
                <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_hora_registro') ?></th>
                <td><?= Html::encode($model->fecha_hora_registro) ?></td>
                 <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                 <td><?= Html::encode($model->user_name) ?></td>
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
    <!-- comienza los tabs -->
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#detalleremision" aria-controls="detalleremision" role="tab" data-toggle="tab">Detalle remision <span class="badge"><?= count($detalle_remision) ?></span></a></li>
            <li role="presentation"><a href="#tallascolores" aria-controls="tallascolores" role="tab" data-toggle="tab">Tallas y colores <span class="badge"><?= count($talla_color) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="detalleremision">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col"  style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Descripcion producto</th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Cantidad</th>       
                                            <th scope="col"  style='background-color:#B9D5CE;'>Vr. unitario</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Subtotal</th>    
                                            <th scope="col"  style='background-color:#B9D5CE;'>Descuento</th>    
                                            <th scope="col"  style='background-color:#B9D5CE;'>% Descueto</th>    
                                            <th scope="col" style='background-color:#B9D5CE; width: 12%'>Total linea</th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_remision as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->codigo_producto ?></td>
                                                <td><?= $val->producto ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->valor_unitario,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                 <td style="text-align: right"><?= ''.number_format($val->porcentaje_descuento,0) ?></td>
                                                 <td style="text-align: right"><?= ''.number_format($val->valor_descuento,0) ?></td>
                                                <td style="text-align: right"><?= '$'.number_format($val->total_linea,0) ?></td>
                                           </tr>
                                         <?php endforeach;?>          
                                    </body>
                                    <tr style="font-size: 90%; background-color:#B9D5CE">
                                        <td colspan="6"></td>
                                        <td style="text-align: right;"><b></b></td>
                                        <td></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="6"></td>
                                        <td style="text-align: right;  background-color:#F0F3EF;"><b>VALOR BRUTO:</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_bruto,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="6"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>DESCTO :</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b> <?= '$ '.number_format($model->descuento,0)?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="6"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>SUBTOTAL:</b></td>
                                        <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->subtotal,0); ?></b></td>
                                    </tr>
                                    <tr style="font-size: 90%;">
                                        <td colspan="6"></td>
                                        <td style="text-align: right; background-color:#F0F3EF"><b>TOTAL PAGAR:</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->total_remision,0); ?></b></td>
                                    </tr>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
                  <div role="tabpanel" class="tab-pane" id="tallascolores">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col"  style='background-color:#B9D5CE;'>Tipo de talla</th>                        
                                            <th scope="col"  style='background-color:#B9D5CE;'>Nombre del color</th>                        
                                             <th scope="col"  style='background-color:#B9D5CE;'>Cantidad vendida</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Fecha registro</th>  
                                            <th scope="col"  style='background-color:#B9D5CE;'>Numero remision</th>  
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($talla_color as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->talla->nombre_talla ?></td>
                                                <td><?= $val->color->colores ?></td>
                                                <td style="text-align: right"><?= ''. number_format($val->cantidad_venta,0) ?></td>
                                                <td><?= $val->fecha_registro ?></td> 
                                                <td style="text-align: right"><?= $val->remision->numero_remision ?></td>
                                           </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                </div>  
                <!-- TERMINA TABS-->
                
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>
