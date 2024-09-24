<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoPago */

$this->title = 'GRUPO DE PAGO';
$this->params['breadcrumbs'][] = ['label' => 'Grupos de Pago', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_grupo_pago;
$view = 'grupo_pago';
?>
<div class="grupo-pago-view">
<!--<?= Html::encode($this->title) ?>-->
      <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_grupo_pago], ['class' => 'btn btn-primary btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Grupo de Pago
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo_pago') ?>:</th>
                    <td><?= Html::encode($model->id_grupo_pago) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'grupo_pago') ?>:</th>
                    <td><?= Html::encode($model->grupo_pago) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_periodo_pago') ?>:</th>
                    <td><?= Html::encode($model->periodoPago->nombre_periodo) ?></td>
                    
                </tr>                
                 <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_departamento') ?>:</th>
                    <td><?= Html::encode($model->departamento->departamento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_municipio') ?>:</th>
                    <td><?= Html::encode($model->municipios->municipio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_sucursal') ?>:</th>
                     <td><?= Html::encode($model->sucursalPila->sucursal) ?></td>
                  
                   
                </tr>   
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'ultimo_pago_prima') ?>:</th>
                    <td><?= Html::encode($model->ultimo_pago_prima) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'ultimo_pago_cesantia') ?>:</th>
                    <td><?= Html::encode($model->ultimo_pago_cesantia) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'dias_pago') ?>:</th>
                    <td><?= Html::encode($model->dias_pago) ?></td>
                </tr>  
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'activo') ?>:</th>
                    <td><?= Html::encode($model->activo) ?></td>  
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_hora_registro') ?>:</th>
                    <td><?= Html::encode($model->fecha_hora_registro) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'limite_devengado') ?>:</th>
                    <td style="text-align: right"><?= Html::encode(''.number_format($model->limite_devengado,0)) ?></td>
                </tr>  
                 <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'observacion') ?>:</th>
                    <td colspan = "6"><?= Html::encode($model->observacion) ?></td>  
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
    ]); ?>        
    <?php ActiveForm::end(); ?>
</div>