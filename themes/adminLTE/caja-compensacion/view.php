<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\CajaCompensacion */

$this->title = 'CAJA DE COMPENSACION';
$this->params['breadcrumbs'][] = ['label' => 'Cajas de Compensacion', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_caja;
?>
<div class="caja-compensacion-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_caja], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Caja de Compensacion
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_caja') ?>:</th>
                    <td><?= Html::encode($model->id_caja) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'caja') ?>:</th>
                    <td><?= Html::encode($model->caja) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'telefono') ?>:</th>
                    <td><?= Html::encode($model->telefono) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'direccion') ?>:</th>
                    <td><?= Html::encode($model->direccion) ?></td>
                </tr>
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo') ?>:</th>
                    <td><?= Html::encode($model->codigo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_municipio') ?>:</th>
                    <td><?= Html::encode($model->codigoMunicipio->municipio) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'activo') ?>:</th>
                    <td><?= Html::encode($model->activo) ?></td>
                    <th style='background-color:#F0F3EF;'></th>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
 
</div>
