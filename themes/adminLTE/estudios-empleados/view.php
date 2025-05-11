<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use app\models\EstudioEmpleado;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConceptoSalarios */

$this->title = 'ESTUDIOS DE EMPLEADOS';
$this->params['breadcrumbs'][] = ['label' => 'Estudios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estudio-empleado-view">

  <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
		<?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
        
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
           Registros
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'id') ?>:</th>
                    <td><?= Html::encode($model->id) ?></td>
                    <th><?= Html::activeLabel($model, 'Documento') ?></th>
                    <td><?= Html::encode($model->documento) ?></td>
                    <th><?= Html::activeLabel($model, 'Empleado') ?></th>
                    <td><?= Html::encode($model->empleado->nombre_completo) ?></td> 
                    <th><?= Html::activeLabel($model, 'institucion_educativa') ?></th>
                    <td><?= Html::encode($model->institucion_educativa) ?></td>
                </tr>     
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'id_profesion') ?></th>
                    <td><?= Html::encode($model->profesion->profesion) ?></td>
                    <th><?= Html::activeLabel($model, 'Municipio') ?></th>
                    <td><?= Html::encode($model->codigoMunicipio->municipio) ?></td>
                    <th><?= Html::activeLabel($model, 'titulo_obtenido') ?></th>
                    <td><?= Html::encode($model->titulo_obtenido) ?></td> 
                    <th><?= Html::activeLabel($model, 'año_cursado') ?></th>
                    <td><?= Html::encode($model->anio_cursado) ?></td>
                </tr>     
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'fecha_inicio') ?></th>
                    <td><?= Html::encode($model->fecha_inicio) ?></td>
                    <th><?= Html::activeLabel($model, 'fecha_terminacion') ?></th>
                    <td><?= Html::encode($model->fecha_terminacion) ?></td> 
                     <th><?= Html::activeLabel($model, 'fecha_vencimiento') ?></th>
                    <td><?= Html::encode($model->fecha_vencimiento) ?></td>
                    <th><?= Html::activeLabel($model, 'Graduado') ?></th>
                    <td><?= Html::encode($model->graduadoEstudio) ?></td>
                </tr>    
                <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'validar_vencimiento') ?></th>
                    <td><?= Html::encode($model->validarestudio) ?></td>
                    <th><?= Html::activeLabel($model, 'Registro') ?></th>
                    <td><?= Html::encode($model->registro) ?></td> 
                    <th><?= Html::activeLabel($model, 'Usuario') ?></th>
                    <td colspan="4"><?= Html::encode($model->user_name) ?></td>
                </tr>     
                 <tr style="font-size: 85%;">
                    <th><?= Html::activeLabel($model, 'observacion') ?></th>
                    <td colspan="8"><?= Html::encode($model->observacion) ?></td>
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
