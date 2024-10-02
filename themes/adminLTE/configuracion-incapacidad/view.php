<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionLicencia */

$this->title = 'Detalle licencia';
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracion-incapacidad-view">
<!--<?= Html::encode($this->title) ?>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' =>$model->codigo_incapacidad], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->codigo_incapacidad], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Eliminar', ['delete', 'id' => $model->codigo_incapacidad], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esta seguro de eliminar el registro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="panel panel-success">
        <div class="panel-heading">
            Configuración licencia
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_incapacidad') ?>:</th>
                    <td><?= Html::encode($model->codigo_incapacidad) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre') ?>:</th>
                    <td><?= Html::encode($model->nombre) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'genera_pago') ?>:</th>
                    <td><?= Html::encode($model->generapago) ?></td>
                </tr>                
                 <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'genera_ibc') ?>:</th>
                    <td><?= Html::encode($model->generaibc) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_salario') ?>:</th>
                     <td colspan="4"><?= Html::encode($model->codigoSalario->nombre_concepto) ?></td>
                </tr>   
                <tr>
                 
        
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