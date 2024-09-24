<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadSalud */

$this->title = 'ENTIDADES DE SALUD';
$this->params['breadcrumbs'][] = ['label' => 'Entidades de Salud', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_entidad_salud;
?>
<div class="entidad-salud-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_entidad_salud], ['class' => 'btn btn-primary']) ?>
	
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Entidades Salud
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_entidad_salud') ?>:</th>
                    <td><?= Html::encode($model->id_entidad_salud) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'entidad_salud') ?>:</th>
                    <td><?= Html::encode($model->entidad_salud) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_interfaz') ?>:</th>
                    <td><?= Html::encode($model->codigo_interfaz) ?></td>  
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'activo') ?>:</th>
                    <td><?= Html::encode($model->activo) ?></td>                    
                </tr>                
            </table>
        </div>
    </div>
</div>