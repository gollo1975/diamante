<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConceptoSalarios */

$this->title = 'Detalle conceptos';
$this->params['breadcrumbs'][] = ['label' => 'Concepto Salarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="concepto-salarios-view">

  <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->codigo_salario], ['class' => 'btn btn-primary btn-sm']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->codigo_salario], ['class' => 'btn btn-success btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Eliminar', ['delete', 'id' => $model->codigo_salario], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Esta seguro de eliminar el registro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Concepto de salarios
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'codigo_salario') ?>:</th>
                    <td><?= Html::encode($model->codigo_salario) ?></td>
                    <th><?= Html::activeLabel($model, 'concepto') ?>:</th>
                    <td><?= Html::encode($model->nombre_concepto) ?></td>
                    <th><?= Html::activeLabel($model, 'compone_salario') ?>:</th>
                    <td><?= Html::encode($model->compone) ?></td> 
                    <th><?= Html::activeLabel($model, 'aplica_porcentaje') ?>:</th>
                    <td><?= Html::encode($model->aplicaporcentaje) ?></td>
                    <th><?= Html::activeLabel($model, 'porcentaje') ?>:</th>
                    <td><?= Html::encode($model->porcentaje) ?>%</td>
                </tr>     
                  <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'porcentaje_tiempo_extra') ?>:</th>
                    <td><?= Html::encode($model->porcentaje_tiempo_extra) ?></td>
                    <th><?= Html::activeLabel($model, 'prestacional') ?>:</th>
                    <td><?= Html::encode($model->prestacion) ?></td>
                    <th><?= Html::activeLabel($model, 'ingreso_base_prestacional') ?>:</th>
                    <td><?= Html::encode($model->ibpprestacion) ?></td>
                    <td><?= Html::activeLabel($model, 'debito_credito') ?>:</td> 
                    <td><?= Html::encode($model->debitocredito) ?></td>
                    <th><?= Html::activeLabel($model, 'adicion') ?>:</th>
                    <td><?= Html::encode($model->Adicion) ?></td>
                </tr>  
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'auxilio_transporte') ?>:</th>
                    <td><?= Html::encode($model->auxilioTransporte) ?></td>
                    <th><?= Html::activeLabel($model, 'concepto_incapacidad') ?>:</th>
                    <td><?= Html::encode($model->conceptoincapacidad) ?></td>
                    <th><?= Html::activeLabel($model, 'concepto_pension') ?>:</th>
                    <td><?= Html::encode($model->conceptopension) ?></td>
                    <td><?= Html::activeLabel($model, 'concepto_salud') ?>:</td> 
                    <td><?= Html::encode($model->conceptosalud) ?></td>
                    <th><?= Html::activeLabel($model, 'concepto_vacacion') ?>:</th>
                    <td><?= Html::encode($model->conceptovacacion) ?></td>
                </tr>   
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'provisiona_vacacion') ?>:</th>
                    <td><?= Html::encode($model->provisionavacacion) ?></td>
                    <th><?= Html::activeLabel($model, 'provisiona_indemnizacion') ?>:</th>
                    <td><?= Html::encode($model->provisionaindemnizacion) ?></td>
                    <th><?= Html::activeLabel($model, 'tipo_adicion') ?>:</th>
                    <td><?= Html::encode($model->tipoadicion) ?></td>
                    <th><?= Html::activeLabel($model, 'recargo_nocturno') ?>:</th>
                    <td><?= Html::encode($model->recargonocturno) ?></td>
                    <td><?= Html::activeLabel($model, 'fecha_creacion') ?>:</td> 
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                </tr>             
                 <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'concepto_comision') ?>:</th>
                    <td><?= Html::encode($model->comision) ?></td>
                    <th><?= Html::activeLabel($model, 'concepto_licencia') ?>:</th>
                    <td><?= Html::encode($model->conceptolicencia) ?></td>
                    <th><?= Html::activeLabel($model, 'fsp') ?>:</th>
                    <td colspan="6"><?= Html::encode($model->fondoSP) ?></td>
                </tr>             
            </table>
        </div>
    </div>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <?php
              $contMaquina = 1;
             ?>
            <li role="presentation" class="active"><a href="#configuracion" aria-controls="configuracion" role="tab" data-toggle="tab">Configuracion  <span class="badge"><?= 1 ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="configuracion">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--TERMINA TABS-->
        </div>
    </div>    
</div>
