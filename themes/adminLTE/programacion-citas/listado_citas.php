<?php
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
$this->title = 'Listado de citas';
$this->params['breadcrumbs'][] = ['label' => 'Listado de citas', 'url' => ['gestion_comercial']];
$this->params['breadcrumbs'][] = $model->id_programacion;
?>
<div class="programacion-citas-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['gestion_comercial'], ['class' => 'btn btn-primary btn-sm']);?>
        <?php if($model->proceso_cerrado == 0 ){
                    echo Html::a('<span class="glyphicon glyphicon-remove"></span> Cerrar programacion', ['cerrar_programacion', 'id' => $model->id_programacion],['class' => 'btn btn-warning btn-sm',
                           'data' => ['confirm' => 'Antes de cerrar la programacio del dia  '. $model->fecha_inicio.', primero debe de hacer la gestion comercial. ¿Esta seguro de cerrar la programación.?', 'method' => 'post']]);
        }?>  
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
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Desde') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Hasta') ?></th>
                    <td><?= Html::encode($model->fecha_final) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'No_citas') ?></th>
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
     <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listadovisitas" aria-controls="listadovisitas" role="tab" data-toggle="tab">Listado de visitas <span class="badge"><?= count($listado_citas) ?></span></a></li>
          
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
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Tipo visita</th>       
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Hora visita</th> 
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Fecha</th>
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'<span title="Visita cumplida">Cump.</span></th> 
                                            <th scope="col" style='background-color:#B9D5CE;'></th> 
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($listado_citas as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->cliente->nombre_completo ?></td>
                                                <td><?= $val->tipoVisita->nombre_visita ?></td>
                                                <td><?= $val->hora_visita ?></td>
                                                 <td><?= $val->fecha_cita_comercial ?></td>
                                                <?php if($val->cumplida == 0){?>
                                                    <td style="color: red"><?= $val->citaCumplida ?></td>
                                                <?php }else{?>
                                                    <td style="color: royalblue"><b><?= $val->citaCumplida ?></b></td>
                                                <?php }
                                                if($model->proceso_cerrado == 0){?>    
                                                    <td style= 'width: 25px; height: 25px;'>
                                                        <?= Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                                        ['/programacion-citas/crear_gestion_comercial','id' =>$model->id_programacion, 'detalle' => $val->id_visita],
                                                          ['title' => 'Crear gestion comercial',
                                                           'data-toggle'=>'modal',
                                                           'data-target'=>'#modalcreargestioncomercial',
                                                          ])    
                                                        ?>
                                                        <div class="modal remote fade" id="modalcreargestioncomercial">
                                                            <div class="modal-dialog modal-lg" style ="width: 435px;">    
                                                                 <div class="modal-content"></div>
                                                            </div>
                                                        </div>   
                                                    </td>
                                                <?php }?>    
                                                    
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>    
                <!--TERMINA TBAS-->
            </div>  
        </div> 
    <?php ActiveForm::end(); ?>  
</div>    
    
