<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionLicencia */

$this->title = 'Detalle configuración licencia';
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracion-licencia-view">
<!--<?= Html::encode($this->title) ?>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' =>$model->codigo_licencia], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->codigo_licencia], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Eliminar', ['delete', 'id' => $model->codigo_licencia], [
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
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_licencia') ?>:</th>
                    <td><?= Html::encode($model->codigo_licencia) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'concepto') ?>:</th>
                    <td><?= Html::encode($model->concepto) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'afecta_salud') ?>:</th>
                    <td><?= Html::encode($model->afectasalud) ?></td>
                </tr>                
                 <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'ausentismo') ?>:</th>
                    <td><?= Html::encode($model->lausentismo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'maternidad') ?>:</th>
                    <td><?= Html::encode($model->lmaternidad) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'paternidad') ?>:</th>
                    <td><?= Html::encode($model->licenciapaternidad) ?></td>
                </tr>   
                 <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'suspension_contrato') ?>:</th>
                    <td><?= Html::encode($model->suspensioncontrato) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'remunerada') ?>:</th>
                    <td><?= Html::encode($model->lremunerada) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_salario') ?>:</th>
                    <td><?= Html::encode($model->codigoSalario->nombre_concepto) ?></td>
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