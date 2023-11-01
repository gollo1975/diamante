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

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Prospectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_prospecto;
$view = 'cliente-prospecto';
?>
<div class="clientes-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 0){?>
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }else{?>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_consulta_clientes'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php }?> 
        <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index','numero' => 10, 'codigo' => $model->id_prospecto,'view' => $view, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
           CLIENTES
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
              
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Tipo documento:</th>
                    <td><?= $model->tipoDocumento->tipo_documento ?></td>
                    <th style='background-color:#F0F3EF;'>Nit/Cedula:</th>
                    <td><?= $model->nit_cedula ?>-<?= $model->dv ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;' >Cliente:</th>
                    <td colspan="3"><?= $model->nombre_completo ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Celular:</th>
                    <td><?= $model->celular ?></td>
                    <th style='background-color:#F0F3EF;'>Direccion:</th>
                    <td><?= $model->direccion_prospecto ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'>Email:</th>
                    <td><?= $model->email_prospecto ?></td>
                    <th style='background-color:#F0F3EF;'>Municipio:</th>
                    <td><?= $model->codigoMunicipio->municipio ?>-<?= $model->codigoDepartamento->departamento ?></td>
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
    <!--INICIO LOS TABS-->
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <?php
              $contMaquina = 1;
             ?>
            <li role="presentation" class="active"><a href="#citas" aria-controls="citas" role="tab" data-toggle="tab">Citas  <span class="badge"><?= count($cita_prospecto) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="citas">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Hora vista</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha visita</th>                        
                                        <th scope="col" style='background-color:#B9D5CE;'>Tipo visita</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($cita_prospecto as $cita):?>
                                    <tr style='font-size:90%;'>
                                        <td> <?= $cita->hora_cita?></td>
                                        <td> <?= $cita->fecha_cita?></td>
                                        <td> <?= $cita->tipoVisita->nombre_visita?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>      
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
