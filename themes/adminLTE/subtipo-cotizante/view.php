<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\SubtipoCotizante */

$this->title = 'SUBTIPO DE COTIZANTE';
$this->params['breadcrumbs'][] = ['label' => 'Subtipos Cotizantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_subtipo_cotizante;
?>
<div class="tipo-cotizante-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_subtipo_cotizante], ['class' => 'btn btn-primary']) ?>
	
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Subtipo Cotizante
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_subtipo_cotizante') ?>:</th>
                    <td><?= Html::encode($model->id_subtipo_cotizante) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'subtipo') ?>:</th>
                    <td><?= Html::encode($model->subtipo) ?></td>  
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_api_nomina') ?>:</th>
                    <td><?= Html::encode($model->codigo_api_nomina) ?></td>  
                </tr>                
            </table>
        </div>
    </div>
</div>