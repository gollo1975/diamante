 <?php

//modelos
use app\models\GrupoProducto;

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

$this->title = 'SOLICITUD DE KITS';
$this->params['breadcrumbs'][] = ['label' => 'Solicitud de its', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_solicitud_armado;
?>
<div class="solicitud-armado-kits-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_orden', 'id' => $model->id_solicitud_armado], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        
        <?php if ($model->autorizado == 0 && $model->proceso_cerrado == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->id_solicitud_armado, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
        } else {
            if ($model->autorizado == 1 && $model->proceso_cerrado == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->id_solicitud_armado, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar solicitud', ['cerrar_solicitud', 'id' => $model->id_solicitud_armado, 'token'=> $token],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Esta seguro de CERRAR y CREAR el consecutivo a la solicitud de KITS.', 'method' => 'post']]);
            }else{
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Visualizar PDF', ['imprimir_solicitud', 'id' => $model->id_solicitud_armado], ['class' => 'btn btn-default btn-sm']);            
                
            }
        }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4>SOLICITUD DE KITS</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id") ?></th>
                    <td><?= Html::encode($model->id_solicitud_armado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_solicitud') ?></th>
                    <td><?= Html::encode($model->solicitud->concepto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_unidades') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_unidades,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_solicitud') ?></th>
                    <td><?= Html::encode($model->fecha_solicitud) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_presentacion') ?></th>
                    <td><?= Html::encode($model->presentacion->descripcion) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_solicitud') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->numero_solicitud) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizadoProceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'proceso_cerrado') ?></th>
                    <td><?= Html::encode($model->procesoCerrado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>                    
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_hora_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_hora_proceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td><?= Html::encode($model->observacion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Total_kits') ?></th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->cantidad_solicitada,0)) ?></td>
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
    ]);
     ?>
    <!-- comienza los tabs -->
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listadopresentacion" aria-controls="listadopresentacion" role="tab" data-toggle="tab">Listado de presentacion <span class="badge"><?= count($detalle) ?></span></a></li>
        </ul>
            <div class="tab-content">
                 <div role="tabpanel" class="tab-pane  active" id="listadopresentacion">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 85%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Id</b></th>   
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Codigo del producto</b></th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Presentacion del producto</b></th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Stock</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidades</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                        <?php
                                        foreach ($detalle as $val):?>
                                            <tr style="font-size: 85%;">
                                                <td><?= $val->id_detalle ?></td>
                                                <td><?= $val->inventario->codigo_producto ?></td>
                                                <td><?= $val->inventario->nombre_producto ?></td>
                                                  <td style='text-align: right'><?= ''.number_format($val->inventario->stock_unidades,0) ?></td>
                                                <td style='text-align: right'><?= ''.number_format($val->cantidad_solicitada,0) ?></td>
                                                <?php if($model->autorizado == 0){?>
                                                    <td style= 'width: 25px; height: 25px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle', 'id' => $model->id_solicitud_armado, 'id_detalle' => $val->id_detalle, 'token' => $token], [
                                                                   'class' => '',
                                                                   'data' => [
                                                                       'confirm' => 'Esta seguro de eliminar el registro?',
                                                                       'method' => 'post',
                                                                   ],
                                                               ])
                                                        ?>
                                                    </td>    
                                                <?php }else{
                                                    if($val->linea_validada == 0){?>
                                                        <td style= 'width: 25px; height: 25px;'>
                                                            <?= Html::a('<span class="glyphicon glyphicon-list"></span> ', ['validar_inventario', 'id' => $model->id_solicitud_armado, 'token' => $token,'id_detalle' => $val->id_detalle], [
                                                                    'class' => '',
                                                                    'title' => 'Proceso que permite validar las existencias en sistemas.)', 
                                                                    'data' => [
                                                                        'confirm' => 'Esta seguro de VALIDAR las existencias de la presentacion   ('.$val->inventario->nombre_producto.').',
                                                                        'method' => 'post',
                                                                    ],
                                                            ]);?>
                                                        </td> 
                                                    <?php }else{?>
                                                        <td style="background-color: #0097bc"><?= 'OK' ?></td>
                                                    <?php }    
                                                } ?>      
                                                     <input type="hidden" name="listado_inventario[]" value="<?= $val->id_detalle?>"> 
                                            </tr>
                                         <?php endforeach;
                                         ?>          
                                    </body>
                                </table>
                            </div>
                             <div class="panel-footer text-right">  
                                <?php 
                                if($model->autorizado == 0){
                                    if(count($detalle) > 0){ ?>
                                       <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Regenerar archivo', ['solicitud-armado-kits/regenerar_formula', 'id' => $model->id_solicitud_armado, 'token' => $token, 'id_presentacion' => $model->id_presentacion],[ 'class' => 'btn btn-info btn-sm']) ?>
                                    <?php }else {?>
                                        <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> Regenerar archivo', ['solicitud-armado-kits/regenerar_formula', 'id' => $model->id_solicitud_armado, 'token' => $token, 'id_presentacion' => $model->id_presentacion],[ 'class' => 'btn btn-info btn-sm']) ?>
                                    <?php }                                
                                } ?>
                            </div>   
                        </div>
                    </div>
                </div>    
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>


   