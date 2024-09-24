<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadPension */

$this->title = 'Detalle Entidad Pension';
$this->params['breadcrumbs'][] = ['label' => 'Entidades de Pension', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_entidad_pension;
?>
<div class="entidad-pension-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id_entidad_pension], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            Entidad Pension
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_entidad_pension') ?>:</th>
                    <td><?= Html::encode($model->id_entidad_pension) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'entidad') ?>:</th>
                    <td><?= Html::encode($model->entidad) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_interfaz') ?>:</th>
                    <td><?= Html::encode($model->codigo_interfaz) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'activo') ?>:</th>
                    <td><?= Html::encode($model->activo) ?></td>                    
                </tr>                
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