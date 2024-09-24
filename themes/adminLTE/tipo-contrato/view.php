<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\TipoContrato */

$this->title = 'TIPO DE CONTRATO';
$this->params['breadcrumbs'][] = ['label' => 'Tipo de contrato', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_tipo_contrato;
?>
<div class="tipo-contrato-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index'] , ['class' => 'btn btn-primary btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Tipo de contrato
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_contrato') ?>:</th>
                    <td><?= Html::encode($model->id_tipo_contrato) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'contrato') ?></th>
                    <td><?= Html::encode($model->contrato) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'estado') ?></th>
                    <td><?= Html::encode($model->activo) ?></td>   
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'prorroga') ?></th>
                    <td><?= Html::encode($model->prorrogaContrato) ?></td>       
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'numero_prorrogas') ?></th>
                    <td><?= Html::encode($model->numero_prorrogas) ?></td>       
                </tr>                
            </table>
        </div>
    </div>
   
</div>