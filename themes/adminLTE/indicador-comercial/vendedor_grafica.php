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

$this->title = 'Detalle (Grafica)';
$this->params['breadcrumbs'][] = ['label' => 'Indicador vendedor', 'url' => ['search_indicador_vendedor']];
$this->params['breadcrumbs'][] = $vendedores->id;
?>
<div class="indicador-comercial-view">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
           <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_indicador_vendedor'], ['class' => 'btn btn-primary btn-sm']);?>
    </p>          
    <div class="panel panel-success">
        <div class="panel-heading">
            INDICADOR COMERCIAL (VENDEDOR)
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($vendedores, "id") ?></th>
                    <td><?= Html::encode($vendedores->id) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($vendedores, 'desde') ?></th>
                    <td><?= Html::encode($vendedores->desde) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($vendedores, 'hasta') ?></th>
                    <td><?= Html::encode($vendedores->hasta) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($vendedores, 'anocierre') ?></th>
                     <td style="text-align: right;"><?= Html::encode($vendedores->indicador->anocierre) ?></td>
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
            <li role="presentation" class="active"><a href="#graficavendedor" aria-controls="graficavendedor" role="tab" data-toggle="tab">Grafica <span class="badge"><?= 1 ?></span></a></li>
            <li role="presentation"><a href="#clientes" aria-controls="clientes" role="tab" data-toggle="tab"> Clientes <span class="badge"><?= count($clientes)?></span></a></li>
        </ul>
            <div class="tab-content">
                 <div role="tabpanel" class="tab-pane active" id="graficavendedor">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <?php
                                     $visita = 0; $visita_real = 0;
                                     $novisita = 0; $eficiencia = 0;
                                    //declaacion 
                                     $visita = $vendedores->total_visitas; 
                                     $visita_real = $vendedores->total_realizadas;    
                                     $novisita = $vendedores->total_no_realizadas;
                                     $eficiencia = $vendedores->total_porcentaje; 
                                    include('grafica_vendedor.php');?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>    
               
                <!--TERMINA TBAS-->
                <div role="tabpanel" class="tab-pane" id="clientes">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                            <div class="panel-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Documento</th>       
                                            <th scope="col" align="center" style='background-color:#B9D5CE;'>Cliente</th>
                                             <th scope="col" align="center" style='background-color:#B9D5CE;'>Agente</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total visitas</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total Reales</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Total pendientes</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>% Eficiencia</th>
                                                                                    
                                        </tr>
                                    </thead>
                                    <body>
                                         <?php
                                         foreach ($clientes as $val):?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->cliente->nit_cedula ?></td>
                                                <td><?= $val->cliente->nombre_completo ?></td>
                                                <td><?= $val->agente->nombre_completo ?></td>
                                                <td style="text-align: right"><?= $val->total_visitas ?></td>
                                                <td style="text-align: right"><?= $val->visita_real ?></td>
                                                <td style="text-align: right"><?= $val->visita_no_real ?></td>
                                                <td style="text-align: right"><?= $val->porcentaje ?>%</td>
                                            </tr>
                                         <?php endforeach;?>          
                                    </body>
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



   