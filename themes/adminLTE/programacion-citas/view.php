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

$this->title = 'Detalle de la cita';
$this->params['breadcrumbs'][] = ['label' => 'Programacion de citas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_programacion;
$view = 'programacion-citas';
?>
<div class="programacion-citas-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
       
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_programacion], ['class' => 'btn btn-primary btn-sm']);?>
     
    </p>          
    
    <div class="panel panel-success">
        <div class="panel-heading">
            DETALLES DE LA CITA
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_final') ?></th>
                    <td><?= Html::encode($model->fecha_final) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_citas') ?></th>
                     <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_citas,0)) ?></td>
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
            <li role="presentation" class="active"><a href="#listadovisitas" aria-controls="listadovisitas" role="tab" data-toggle="tab">Listado de visitas <span class="badge"><?= count($detalle_visita) ?></span></a></li>
          
        </ul>
            <div class="tab-content">
                 <div role="tabpanel" class="tab-pane active" id="listadovisitas">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-responsive-lg">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cliente</th>       
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Visita</th>       
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Hora</th>    
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Motivo</th>  
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($detalle_visita as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->cliente->nombre_completo ?></td>
                                                 <td><?= $val->tipoVisita->nombre_visita ?></td>
                                                <td><?= $val->hora_visita ?></td>
                                                <td><?= $val->nota ?></td>
                                                <?php if($model->proceso_cerrado == 0 && $tokenAcceso == 3){?>
                                                    <td style= 'width: 25px; height: 25px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                                           ['/programacion-citas/editar_cita_cliente', 'id' => $model->id_programacion, 'detalle' => $val->id_visita, 'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso],
                                                             ['title' => 'Editar la cita para cliente',
                                                              'data-toggle'=>'modal',
                                                              'data-target'=>'#modaleditarcitacliente',
                                                             ])    
                                                       ?>
                                                       <div class="modal remote fade" id="modaleditarcitacliente">
                                                            <div class="modal-dialog modal-lg" style ="width: 450px;">    
                                                                <div class="modal-content"></div>
                                                            </div>
                                                       </div>
                                                    </td>    
                                                    <td style= 'width: 25px; height: 25px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar_detalle', 'id' => $model->id_programacion, 'detalle' => $val->id_visita, 'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso], [
                                                                    'class' => '',
                                                                    'data' => [
                                                                        'confirm' => 'Esta seguro de eliminar la vista comercial.?',
                                                                        'method' => 'post',
                                                                    ],
                                                                ])
                                                        ?>
                                                    </td>
                                                <?php }?>    
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <?php if($model->proceso_cerrado == 0 && $tokenAcceso == 3){?>
                                <div class="panel-footer text-right">
                                    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Crear',
                                    ['/programacion-citas/crear_nueva_cita','id' =>$model->id_programacion, 'agenteToken' => $agenteToken, 'tokenAcceso' => $tokenAcceso],
                                      ['title' => 'Crear nueva cita para el cliente',
                                       'data-toggle'=>'modal',
                                       'data-target'=>'#modalcrearnuevacita',
                                       'class' => 'btn btn-info btn-xs'
                                      ])    
                                    ?>
                                    <div class="modal remote fade" id="modalcrearnuevacita">
                                        <div class="modal-dialog modal-lg" style ="width: 460px;">    
                                             <div class="modal-content"></div>
                                        </div>
                                    </div>   
                                </div>    
   
                            <?php }else{?>
                                <div class="panel-footer text-right">
                                    <?php if($model->proceso_cerrado == 1){ ?>
                                      <div class="panel-footer text-right">
                                        <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['excel_citas', 'id' => $model->id_programacion], ['class' => 'btn btn-primary btn-sm']);?>
                                      </div>  
                                     <?php }?>
                                </div>     
                           <?php }?>
                        </div>
                    </div>
                </div>    
                <!--TERMINA TBAS-->
            </div>  
        </div>             
        <?php ActiveForm::end(); ?>  
</div>

   