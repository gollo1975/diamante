<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Municipio */

$this->title = 'Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Tipo de documentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_tipo_documento;
?>
<div class="tipo-documento-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_tipo_documento], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id_tipo_documento], ['class' => 'btn btn-success btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            TIPO DE DOCUMENTOS
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
               <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_tipo_documento') ?>:</th>
                    <td><?= Html::encode($model->id_tipo_documento) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'tipo_documento') ?>:</th>
                    <td><?= Html::encode($model->tipo_documento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'documento') ?>:</th>
                    <td><?= Html::encode($model->documento) ?></td>                    
              </tr>
                <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'proceso_nomina') ?>:</th>
                    <td><?= Html::encode($model->procesoNomina) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'proceso_cliente') ?>:</th>
                    <td><?= Html::encode($model->procesoCliente) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'proceso_proveedor') ?>:</th>
                    <td><?= Html::encode($model->procesoProveedor) ?></td>                    
                </tr>    
                 <tr style ='font-size:90%;'>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_interfaz') ?>:</th>
                    <td><?= Html::encode($model->codigo_interfaz) ?></td>                    
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_registro') ?>:</th>
                    <td><?= Html::encode($model->fecha_registro) ?></td>
                    <th style='background-color:#F0F3EF;'></th>
                    <td></td>                    
                </tr>    
            </table>
        </div>
    </div>

</div>