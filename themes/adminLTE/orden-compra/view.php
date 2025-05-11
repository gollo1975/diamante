<?php

//modelos
use app\models\Items;
use app\models\SolicitudCompra;
use app\models\SolicitudCompraDetalles;
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

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Orden de compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_orden_compra;
$view = 'orden-compra';
?>

<?php
    //$remision = Remision::find()->where(['=', 'idordenproduccion', $model->idordenproduccion])->one();
?>

<div class="orden-compra-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
            <?php if($token == 0){?>
                <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_orden_compra], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php }else{?>
                <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_orden_compra', 'id' => $model->id_orden_compra], ['class' => 'btn btn-primary btn-sm']) ?>
            <?php }?>
            <?php if ($model->autorizado == 0 && $model->numero_orden == 0) { ?>
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_orden_compra], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_orden_compra, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
            } else {
                if ($model->autorizado == 1 && $model->numero_orden == 0){
                    echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_orden_compra, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
                      echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar solicitud', ['cerrarsolicitud', 'id' => $model->id_orden_compra, 'token'=> $token],['class' => 'btn btn-warning btn-sm',
                               'data' => ['confirm' => 'Esta seguro de cerrar la orden de compra.', 'method' => 'post']]);
                    echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimirordencompra', 'id' => $model->id_orden_compra], ['class' => 'btn btn-default btn-sm']);            
                }else{
                    echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimirordencompra', 'id' => $model->id_orden_compra], ['class' => 'btn btn-default btn-sm']);            
                    echo Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 5, 'codigo' => $model->id_orden_compra,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']);
                }
            }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            ORDENES DE COMPRAS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_orden_compra") ?></th>
                    <td><?= Html::encode($model->id_orden_compra) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_orden') ?></th>
                    <td><?= Html::encode($model->tipoOrden->descripcion_orden) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_proveedor') ?></th>
                    <td><?= Html::encode($model->proveedor->nombre_completo) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'subtotal') ?></th>
                     <td style="text-align: right;"><?= Html::encode(''.number_format($model->subtotal,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_solicitud') ?></th>
                    <td><?= Html::encode($model->numero_solicitud) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_orden')?></th>
                    <td><?= Html::encode($model->numero_orden) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'impuesto') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->impuesto,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                   
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_entrega') ?></th>
                    <td><?= Html::encode($model->fecha_entrega) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_orden') ?></th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->total_orden,0)) ?></td>                    
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoCompra) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td  colspan="6"><?= Html::encode($model->observacion) ?></td>
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
            <li role="presentation" class="active"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Items <span class="badge"><?= count($detalle_compras) ?></span></a></li>
        </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="items">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Insumos</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Iva</th>       
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Vr. unitario</th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Impuesto</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Subtotal</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Total</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_compras as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->items->codigo ?></td>
                                                <td><?= $val->items->descripcion ?></td>
                                                <td><?= $val->porcentaje ?></td>
                                                <?php if($model->autorizado == 0){?>
                                                    <td style="padding-right: 1;padding-right: 0;"><input type="text" name="cantidad[]" value="<?= $val->cantidad ?>" size="9" required="true"> </td> 
                                                    <td style="padding-right: 1;padding-right: 0;"><input type="text" name="valor[]" value="<?= $val->valor ?>" size="9"> </td> 
                                                <?php }else{?>
                                                  <td style="text-align: right"><?= ''.number_format($val->cantidad,0) ?></td>
                                                  <td style="text-align: right"><?= ''.number_format($val->valor,0) ?></td>
                                                <?php }?>   
                                                <td style="text-align: right"><?= ''.number_format($val->valor_iva,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->subtotal,0) ?></td>
                                                <td style="text-align: right"><?= ''.number_format($val->total_orden,0) ?></td>
                                                <input type="hidden" name="detalle_compra[]" value="<?= $val->id_detalle ?>">
                                                <td style= 'width: 25px; height: 25px;'>
                                                        <?php 
                                                        if($model->autorizado == 0){?>
                                                           <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar', 'id' => $model->id_orden_compra, 'detalle' => $val->id_detalle, 'token' => $token], [
                                                                      'class' => '',
                                                                      'data' => [
                                                                          'confirm' => 'Esta seguro de eliminar el registro?',
                                                                          'method' => 'post',
                                                                      ],
                                                                  ])
                                                           ?>
                                                        <?php } ?> 
                                                    </div>    
                                                </td>     
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <div class="panel-footer text-right">  
                                <?php 
                                if($model->autorizado == 0){?>
                                     <?= Html::a('<span class="glyphicon glyphicon-export"></span> Importar solicitud', ['orden-compra/importarsolicitud', 'id' => $model->id_orden_compra, 'token' => $token,'id_solicitud' => $model->id_tipo_orden],[ 'class' => 'btn btn-primary btn-sm']) ?>                                            
                                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizaregistro']) ?>
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

   