<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionCredito */

$this->title = 'Tipo de  credito';
$this->params['breadcrumbs'][] = ['label' => 'Configuracion credito', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracion-incapacidad-view">
<!--<?= Html::encode($this->title) ?>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' =>$model->codigo_credito], ['class' => 'btn btn-primary btn-sm']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->codigo_credito], ['class' => 'btn btn-success btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Eliminar', ['delete', 'id' => $model->codigo_credito], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Esta seguro de eliminar el registro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<div class="panel panel-success">
        <div class="panel-heading">
            Configuración crédito
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_credito') ?>:</th>
                    <td><?= Html::encode($model->codigo_credito) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_credito') ?>:</th>
                    <td><?= Html::encode($model->nombre_credito) ?></td>
                </tr>
                <tr style="font-size: 85%;">
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_salario') ?>:</th>
                     <td colspan="4"><?= Html::encode($model->conceptoSalarios->nombre_concepto) ?></td>
                </tr>   
            </table>
        </div>
    </div>
   
</div>