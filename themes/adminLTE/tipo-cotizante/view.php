<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\TipoCotizante */

$this->title = 'TIPOS DE COTIZANTES';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Cotizantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_tipo_cotizante;
?>
<div class="tipo-cotizante-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_tipo_cotizante], ['class' => 'btn btn-primary']) ?>
	
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Tipo Cotizante
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_cotizante') ?>:</th>
                    <td><?= Html::encode($model->id_tipo_cotizante) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'tipo') ?>:</th>
                    <td><?= Html::encode($model->tipo) ?></td>  
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_interfaz') ?>:</th>
                    <td><?= Html::encode($model->codigo_interfaz) ?></td>  
                </tr>                
            </table>
        </div>
    </div>
   
</div>