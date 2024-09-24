<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\CentroTrabajo */

$this->title = 'CENTRO DE TRABAJO';
$this->params['breadcrumbs'][] = ['label' => 'Centros de Trabajo', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_centro_trabajo;
?>
<div class="centro-trabajo-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_centro_trabajo], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Centro de Trabajo
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th><?= Html::activeLabel($model, 'id_centro_trabajo') ?>:</th>
                    <td><?= Html::encode($model->id_centro_trabajo) ?></td>
                    <th><?= Html::activeLabel($model, 'centro_trabajo') ?>:</th>
                    <td><?= Html::encode($model->centro_trabajo) ?></td>
                    <th><?= Html::activeLabel($model, 'activo') ?>:</th>
                    <td><?= Html::encode($model->activo) ?></td>                    
                </tr>                
            </table>
        </div>
    </div>
</div>