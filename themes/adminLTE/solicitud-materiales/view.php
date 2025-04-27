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

$this->title = 'SOLICITUD MATERIAL DE EMPAQUE';
$this->params['breadcrumbs'][] = ['label' => 'Solicitud de materiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->codigo;
$view = 'solicitud-materiales';
?>
<div class="solicitud-materiales-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->codigo], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_orden', 'id' => $model->codigo], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?>
        
        <?php if ($model->autorizado == 0 && $model->cerrar_solicitud == 0) { ?>
            <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Autorizar', ['autorizado', 'id' => $model->codigo, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
        } else {
            if ($model->autorizado == 1 && $model->cerrar_solicitud == 0){
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Desautorizar', ['autorizado', 'id' => $model->codigo, 'token' => $token], ['class' => 'btn btn-default btn-sm']);
                echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar solicitud', ['cerrar_solicitud', 'id' => $model->codigo, 'token'=> $token],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Esta seguro de CERRAR y CREAR el consecutivo a la solicitud de materiales.', 'method' => 'post']]);
            }else{
                echo Html::a('<span class="glyphicon glyphicon-print"></span> Imprimir', ['imprimir_solicitud_materiales', 'id' => $model->codigo], ['class' => 'btn btn-default btn-sm']);            
                echo Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 19, 'codigo' => $model->codigo,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']);
            }
        }?>        
    </p>  
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4>SOLICITUD DE MATERIALES</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id") ?></th>
                    <td><?= Html::encode($model->codigo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_producto') ?></th>
                    <td><?= Html::encode($model->productos->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_solicitud') ?></th>
                    <td><?= Html::encode($model->solicitud->descripcion) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'unidades') ?></th>
                     <td style="text-align: right;"><?= Html::encode(''.number_format($model->unidades,0)) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_solicitud') ?></th>
                    <td><?= Html::encode($model->numero_solicitud) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_cierre') ?></th>
                    <td><?= Html::encode($model->fecha_cierre) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_hora_registro) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_lote') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->numero_lote) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_orden_produccion')?></th>
                    <td><?= Html::encode($model->ordenProduccion->numero_orden) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'autorizado') ?></th>
                    <td><?= Html::encode($model->autorizado) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'cerrar_solicitud') ?></th>
                    <td><?= Html::encode($model->cerrarSolicitud) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>                    
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td  colspan="8"><?= Html::encode($model->observacion) ?></td>
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
            <li role="presentation" class="active"><a href="#presentacion" aria-controls="presentacion" role="tab" data-toggle="tab">Presentacion de producto <span class="badge"><?= count($presentacion) ?></span></a></li>
            <li role="presentation"><a href="#detallesolicitud" aria-controls="detallesolicitud" role="tab" data-toggle="tab">Materia de empaque <span class="badge"><?= count($detalle_solicitud) ?></span></a></li>
        </ul>
            <div class="tab-content">
                 <div role="tabpanel" class="tab-pane  active" id="presentacion">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 85%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Id</b></th>   
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Codigo</b></th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Presentacion</b></th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad</th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($presentacion as $val):?>
                                            <tr style="font-size: 85%;">
                                                <td><?= $val->id_detalle ?></td>
                                                <td><?= $val->codigo_producto ?></td>
                                                <td><?= $val->descripcion ?></td>
                                                <td style="text-align: right"><?= $val->cantidad_real ?></td>
                                                <?php if($model->autorizado == 0){
                                                    $resp = \app\models\SolicitudMaterialesDetalle::find()->where(['=','id_detalle', $val->id_detalle])->one();
                                                    if(!$resp){?>
                                                        <td style= 'width: 20px; height: 20px;'>
                                                            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['buscar_material_empaque', 'id' => $model->codigo, 'token' => $token,'id_detalle' => $val->id_detalle], [
                                                                           'class' => '',
                                                                           'title' => 'Proceso que permite descargar el material de empaque.)', 
                                                                           'data' => [
                                                                               'confirm' => 'Esta seguro de importar el material de empaque a la presentacion de producto  ('.$val->descripcion.').',
                                                                               'method' => 'post',
                                                                           ],
                                                             ])?>
                                                        </td>    
                                                        <td style= 'width: 20px; height: 20px;'> </td> 
                                                       
                                                    <?php }else{
                                                        if($val->solicitud_empaque == 0){ ?>
                                                        
                                                            <td style= 'width: 20px; height: 20px;'>
                                                                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ', ['buscar_material_empaque', 'id' => $model->codigo, 'token' => $token,'id_detalle' => $val->id_detalle], [
                                                                               'class' => '',
                                                                               'title' => 'Proceso que permite descargar el material de empaque.)', 
                                                                               'data' => [
                                                                                   'confirm' => 'Esta seguro de importar el material de empaque a la presentacion de producto  '.$val->descripcion.'.',
                                                                                   'method' => 'post',
                                                                               ],
                                                                ])?>
                                                            </td>    
                                                            <td style= 'width: 20px; height: 20px;'>    
                                                                <?= Html::a('<span class="glyphicon glyphicon-eye-close"></span> ', ['cerrar_presentacion', 'id' => $model->codigo, 'token' => $token,'id_detalle' => $val->id_detalle], [
                                                                               'class' => '',
                                                                               'title' => 'Proceso que permite cerrar la presentacion.)', 
                                                                               'data' => [
                                                                                   'confirm' => 'Esta seguro de CERRAR la presentacion de producto  ('.$val->descripcion.').',
                                                                                   'method' => 'post',
                                                                               ],
                                                                 ])?>
                                                            </td>  
                                                        <?php }else{?>
                                                            <td style= 'width: 20px; height: 20px;'></td>
                                                            <td style= 'width: 20px; height: 20px;'></td>
                                                        <?php }    
                                                    }         
                                                }else{?>
                                                    <td style="width: 25px; height: 25px;"></td>
                                                    <td style="width: 25px; height: 25px;"></td>
                                                <?php }   ?>      
                                                   
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
                <div role="tabpanel" class="tab-pane" id="detallesolicitud">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr style="font-size: 85%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Id</b></th>                        
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'><b>Código del material</b></th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre del material</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Presentacion producto</th> 
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Unidades del lote</th>  
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Unidades solicitadas</th>
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_solicitud as $val):
                                             ?>
                                            <tr style="font-size: 85%;">
                                                <td><?= $val->id ?></td>
                                                <td><?= $val->codigo_materia ?></td>
                                                <td><?= $val->materiales ?></td>
                                                <td><?= $val->ordenPresentacion->descripcion ?></td>
                                                <td style="text-align: right"><?= $val->unidades_lote ?></td>
                                                <?php if($model->autorizado == 0 && $val->linea_cerrada == 0){?>
                                                    <td style="padding-right: 1;padding-right: 1; text-align: right"> <input type="text" name="unidades_requeridas[]" style ="text-align: right" value="<?= $val->unidades_requeridas ?>" size ="12" required="true"> </td> 
                                                <?php }else{?>
                                                    <td style='text-align: right'><?= ''.number_format($val->unidades_requeridas,0) ?></td>
                                                <?php }?>   
                                                <?php if($model->autorizado == 0){?>
                                                    <td style= 'width: 25px; height: 25px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle', 'id' => $model->codigo, 'id_detalle' => $val->id, 'token' => $token], [
                                                                   'class' => '',
                                                                   'data' => [
                                                                       'confirm' => 'Esta seguro de eliminar el registro?',
                                                                       'method' => 'post',
                                                                   ],
                                                               ])
                                                        ?>
                                                    </td>    
                                                <?php }else{?>
                                                    <td style="width: 25px; height: 25px;"></td>
                                                <?php }   ?>      
                                                     <input type="hidden" name="listado_materiales[]" value="<?= $val->id?>"> 
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <div class="panel-footer text-right">  
                                <?php 
                                if($model->autorizado == 0){
                                    if(count($detalle_solicitud) > 0){ ?>
                                        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success btn-sm", 'name' => 'actualizar_cantidad']) ?>
                                            
                                    <?php }
                                } ?>
                            </div>   
                        </div>
                    </div>
                </div>    
                <!-- TERMINA TABS -->
            </div>  
    </div>
  <?php ActiveForm::end(); ?>  
</div>


   