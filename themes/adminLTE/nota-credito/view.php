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

$this->title = 'Detalle nota credito';
$this->params['breadcrumbs'][] = ['label' => 'Nota crédito', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_nota;
?>

<?php
    //$remision = Remision::find()->where(['=', 'idordenproduccion', $model->idordenproduccion])->one();
?>

<div class="nota-credito-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php if ($model->autorizado == 0 && $model->numero_nota_credito == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_nota, 'id_factura' => $model->id_factura], ['class' => 'btn btn-default btn-sm']);?>
           <!--Comienza el ajas-->
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar nota',
                    ['/nota-credito/editar_nota', 'id' => $model->id_nota],
                    [
                        'title' => 'Permite editar los campors de motivo y observaciones',
                        'data-toggle'=>'modal',
                        'data-target'=>'#modaleditarnota',
                        'class' => 'btn btn-success btn-sm',
                        'data-backdrop' =>'static'
                    ])    
                    ?>
             <div class="modal remote fade" id="modaleditarnota">
                       <div class="modal-dialog modal-lg" style ="width: 485px;"> 
                         <div class="modal-content"></div>
                     </div>
             </div>
        <?php } else {
            if ($model->autorizado == 1 && $model->numero_nota_credito == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_nota, 'id_factura' => $model->id_factura], ['class' => 'btn btn-default btn-sm']);
                echo Html::a('<span class="glyphicon glyphicon-book"></span> Generar nota crédito', ['generar_nota_credito', 'id' => $model->id_nota,'id_factura' => $model->id_factura],['class' => 'btn btn-info btn-sm',
                           'data' => ['confirm' => 'Esta seguro de generar la Nota Credito  al cliente '.$model->cliente.' para ser enviada a la Dian. Despues de este proceso No se puede modificar el documento.', 'method' => 'post']]);
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_nota_credito', 'id' => $model->id_nota], ['class' => 'btn btn-default btn-sm']);            
            }else{
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_nota_credito', 'id' => $model->id_nota], ['class' => 'btn btn-default btn-sm']);            
                echo Html::a('<span class="glyphicon glyphicon-list"></span> Enviar a la Dian', ['enviar_nota_dian', 'id' => $model->id_nota],['class' => 'btn btn-success btn-sm',
                           'data' => ['confirm' => 'Esta seguro que desea enviar la Nota Crédito a la Dian. Tener presente que este documento ya no se puede modificar.', 'method' => 'post']]);
            }
        }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
           NOTA CREDITO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_nota") ?></th>
                    <td><?= Html::encode($model->id_nota) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "numero_nota_credito") ?></th>
                    <td><?= Html::encode($model->numero_nota_credito) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nit_cedula') ?></th>
                    <td><?= Html::encode($model->nit_cedula) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Cliente') ?></th>
                    <td><?= Html::encode($model->cliente) ?></td>
                  
                </tr>
                <tr style="font-size: 90%;">
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Numero_factura') ?></th>
                     <td><?= Html::encode($model->factura->numero_factura) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_factura') ?></th>
                    <td><?= Html::encode($model->fecha_factura) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_factura') ?></th>
                    <td><?= Html::encode($model->tipoFactura->descripcion) ?></td>
                    <?php if($model->id_motivo == ''){?>
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_motivo') ?></th>
                        <td style="background-color: moccasin"><?= Html::encode('NO FOUND') ?></td>
                    <?php }else{?>    
                        <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_motivo') ?></th>
                        <td><?= Html::encode($model->motivo->concepto) ?></td>
                    <?php }?>    
                                    
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoNota) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cerrar_nota')?></th>
                    <td><?= Html::encode($model->cerrarNota) ?> </td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name')?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Saldo_factura') ?>:</th>
                            <td style="text-align: right;"><?= Html::encode('$ '.number_format($model->factura->saldo_factura,0)) ?></td>
     
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="7"><?= Html::encode($model->observacion) ?></td>
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
            <li role="presentation" class="active"><a href="#detallenota" aria-controls="detallenota" role="tab" data-toggle="tab">Detalle nota <span class="badge"><?= count($detalle_nota) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="detallenota">
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
                                            <th scope="col"  style='background-color:#B9D5CE; width: 12%'>Impuesto</th>  
                                            <th scope="col" style='background-color:#B9D5CE; width: 12%'>Total linea</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_nota as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->codigo_producto ?></td>
                                                <td><?= $val->producto ?></td>
                                                <td style="text-align: right"> <?= ''.number_format($val->cantidad,0) ?> </td> 
                                                <td style="text-align: right"><?= '$ '.number_format($val->valor_unitario,2) ?></td>
                                                <td style="text-align: right"><?= '$ '.number_format($val->subtotal,2) ?></td>
                                                <td style="text-align: right"><?= '$ '.number_format($val->impuesto,2) ?></td>
                                                <td style="text-align: right"><?= '$ '.number_format($val->total_linea,2) ?></td>
                                                <?php if($model->autorizado == 0){?>
                                                    <td style= 'width: 20px; height: 20px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-refresh"></span>',
                                                        ['/nota-credito/editar_linea_nota','id' =>$model->id_nota, 'detalle' => $val->id_detalle, 'id_factura' => $model->id_factura],
                                                          ['title' => 'Modificar las cantidades a devolver',
                                                           'data-toggle'=>'modal',
                                                           'data-target'=>'#modaleditarlineanota',
                                                            'data-backdrop' =>'static'
                                                          ])    
                                                        ?>
                                                        <div class="modal remote fade" id="modaleditarlineanota">
                                                            <div class="modal-dialog modal-lg" style ="width: 435px;">    
                                                                 <div class="modal-content"></div>
                                                            </div>
                                                        </div>   
                                                    </td>
                                                    <td style= 'width: 20px; height: 20px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle', 'id' => $model->id_nota, 'detalle' => $val->id_detalle, 'id_factura' => $model->id_factura], [
                                                            'class' => '',
                                                            'data' => [
                                                                'confirm' => 'Esta seguro de eliminar el registro?',
                                                                'method' => 'post',
                                                            ],
                                                        ])
                                                        ?>
                                                   </td>    
                                                <?php }else{?>
                                                   <td style= 'width: 20px; height: 20px;'></td>
                                                   <td style= 'width: 20px; height: 20px;'></td>
                                                <?php }?>   
                                           </tr>
                                         <?php endforeach;?>          
                                    </body>
                                    <tr style="font-size: 90%; background-color:#B9D5CE">
                                        <td colspan="7"></td>
                                        <td style="text-align: right;"><b></b></td>
                                        <td></td>
                                    </tr>

                                    <tr style="font-size: 95%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: left; background-color:#F0F3EF"><b>Subtotal:</b></td>
                                        <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_bruto,0); ?></b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr style="font-size: 95%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: left; background-color:#F0F3EF"><b>Impuesto (<?= $model->factura->porcentaje_iva?> %) :</b></td>
                                        <td align="right" style=" background-color:#F0F3EF" ><b><?= '$ '.number_format($model->impuesto,0); ?></b></td>
                                         <td></td>
                                         <td></td>
                                    </tr>
                                    <tr style="font-size: 95%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: left; background-color:#F0F3EF"><b>Retención (<?= $model->factura->porcentaje_rete_fuente?> %) :</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->retencion,0); ?></b></td>
                                         <td></td>
                                         <td></td>
                                    </tr>
                                    <tr style="font-size: 95%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: left; background-color:#F0F3EF"><b>Rete Iva (<?= $model->factura->porcentaje_rete_iva?> %) :</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->rete_iva,0); ?></b></td>
                                         <td></td>
                                         <td></td>
                                    </tr>
                                    <tr style="font-size: 95%;">
                                        <td colspan="5"></td>
                                        <td style="text-align: left; background-color:#F0F3EF"><b>Total nota crédito:</b></td>
                                        <td align="right" style="background-color:#F0F3EF" ><b><?= '$ '.number_format($model->valor_total_devolucion,0); ?></b></td>
                                         <td></td>
                                         <td></td>
                                    </tr>
                                </table>
                                <?php if($model->autorizado == 0){?>
                                    <div class="panel-footer text-right">
                                          <?= Html::a('<span class="glyphicon glyphicon-plus"></span>Cargar detalle', ['nota-credito/listar_detalle_factura', 'id' => $model->id_nota, 'id_factura' => $model->id_factura ],[ 'class' => 'btn btn-success btn-sm']) ?>                                            
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>
