<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\ConceptoSalarios;

$this->title = 'Editar prestaciones';
?>
<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
            'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
            'options' => []
        ],
        ]);
$conceptosalario = ArrayHelper::map(ConceptoSalarios::find()->where(['tipo_adicion'=> 1])->orderBy('nombre_concepto ASC')->all(), 'codigo_salario', 'nombre_concepto');

?>
        <div class="panel panel-success">
            <div class="panel-heading">
                Editar concepto
            </div>
            <div class="panel-body">
                <div class="row">
                    <?= $form->field($model, 'nro_dias')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'dias_ausentes')->textInput(['maxlength' => true]) ?>
                </div>        
                <div class="row">
                    <?= $form->field($model, 'salario_promedio_prima')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'total_dias')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="row">
                    <?= $form->field($model, 'auxilio_transporte')->textInput(['maxlength' => true]) ?>
                </div>
              
                <div class="panel-footer text-right">			
                    <a href="<?= Url::toRoute(["prestaciones-sociales/view", 'id' => $id, 'pagina' => $pagina]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>
                    <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success btn-sm",]) ?>
                </div>
            </div>
        </div>
<?php $form->end() ?>    



