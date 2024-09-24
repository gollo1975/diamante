<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\TipoContrato */

$this->title = 'TIEMPO DE TRABAJO';
$this->params['breadcrumbs'][] = ['label' => 'Tiempo de servicio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_tiempo;
?>
<div class="tiempo-servicio-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_tiempo], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Tipo de recibo
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tiempo') ?>:</th>
                    <td><?= Html::encode($model->id_tiempo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'tiempo_servicio') ?>:</th>
                    <td><?= Html::encode($model->tiempo_servicio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'horas_dia') ?>:</th>
                    <td><?= Html::encode($model->horas_dia) ?></td>                    
                </tr>   
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'pago_incapacidad_general') ?>:</th>
                    <td><?= Html::encode($model->pago_incapacidad_general) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'pago_incapacidad_laboral') ?>:</th>
                    <td colspan="6"><?= Html::encode($model->pago_incapacidad_laboral) ?></td>
                 
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