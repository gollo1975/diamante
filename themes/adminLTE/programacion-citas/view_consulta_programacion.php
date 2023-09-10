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

$this->title = 'Detalle de la programacion';
$this->params['breadcrumbs'][] = ['label' => 'Consulta programacion de citas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_programacion;
?>
<div class="programacion-citas-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
       
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_programacion_citas'], ['class' => 'btn btn-primary btn-sm']);?>
     
    </p>          
    
    <div class="panel panel-success">
        <div class="panel-heading">
            DETALLES DE LA CITA
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "id_programacion") ?></th>
                    <td><?= Html::encode($model->id_programacion) ?></td>
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
            <li role="presentation" class="active"><a href="#listadocitas" aria-controls="listadocitas" role="tab" data-toggle="tab">Listado de citas <span class="badge"><?= count($detalle_visita) ?></span></a></li>
            <li role="presentation"><a href="#graficacitas" aria-controls="graficacitas" role="tab" data-toggle="tab">Gráfica de citas<span class="badge"></span></a></li>
        </ul>
            <div class="tab-content">
                 <div role="tabpanel" class="tab-pane active" id="listadocitas">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cliente</th>       
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Visita</th>       
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Hora</th>    
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Motivo</th>  
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cumplida</th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Fecha/Hora</th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE; width: 30%'>Gestión</th>
                                          
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
                                                <?php if($val->cumplida == 0){?>
                                                    <td style="color: red"><?= $val->citaCumplida ?></td>
                                                <?php }else{?>
                                                    <td style="color: royalblue"><b><?= $val->citaCumplida ?></b></td>
                                                <?php }?>
                                                <td><?= $val->fecha_informe ?></td>
                                                <td><?= $val->descripcion_gestion ?></td>
                                                
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                            <div class="panel-footer text-right">
                                    <?= Html::a('<span class="glyphicon glyphicon-download-alt"></span> Expotar excel', ['excel_citas', 'id' => $model->id_programacion], ['class' => 'btn btn-primary btn-sm']);?>
                            </div>     
                           
                        </div>
                    </div>
                </div>    
               
                <!--TERMINA TBAS-->
                <div role="tabpanel" class="tab-pane" id="graficacitas">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-responsive-lg">
                                     <?php 
                                        $con_citas = $model->total_citas;
                                        $visita_real = $model->visitas_cumplidas;
                                        $no_visitas = $model->visitas_no_cumplidas;
                                        $nombre = $model->agente->nombre_completo;
                                        include('indicador_programacion_citas.php'); ?> 
                                </table>
                            </div>     
                        </div>
                    </div>
                </div>
                <!--TERMINA TABS-->
            </div>  
        </div>             
        <?php ActiveForm::end(); ?>  
</div>

   