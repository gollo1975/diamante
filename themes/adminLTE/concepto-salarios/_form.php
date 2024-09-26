<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConceptoSalarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="concepto-salarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigo_salario')->textInput() ?>

    <?= $form->field($model, 'nombre_concepto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'compone_salario')->textInput() ?>

    <?= $form->field($model, 'inicio_nomina')->textInput() ?>

    <?= $form->field($model, 'aplica_porcentaje')->textInput() ?>

    <?= $form->field($model, 'porcentaje')->textInput() ?>

    <?= $form->field($model, 'porcentaje_tiempo_extra')->textInput() ?>

    <?= $form->field($model, 'prestacional')->textInput() ?>

    <?= $form->field($model, 'ingreso_base_prestacional')->textInput() ?>

    <?= $form->field($model, 'ingreso_base_cotizacion')->textInput() ?>

    <?= $form->field($model, 'debito_credito')->textInput() ?>

    <?= $form->field($model, 'adicion')->textInput() ?>

    <?= $form->field($model, 'auxilio_transporte')->textInput() ?>

    <?= $form->field($model, 'concepto_incapacidad')->textInput() ?>

    <?= $form->field($model, 'concepto_pension')->textInput() ?>

    <?= $form->field($model, 'concepto_salud')->textInput() ?>

    <?= $form->field($model, 'concepto_vacacion')->textInput() ?>

    <?= $form->field($model, 'provisiona_vacacion')->textInput() ?>

    <?= $form->field($model, 'provisiona_indemnizacion')->textInput() ?>

    <?= $form->field($model, 'tipo_adicion')->textInput() ?>

    <?= $form->field($model, 'recargo_nocturno')->textInput() ?>

    <?= $form->field($model, 'hora_extra')->textInput() ?>

    <?= $form->field($model, 'concepto_comision')->textInput() ?>

    <?= $form->field($model, 'concepto_licencia')->textInput() ?>

    <?= $form->field($model, 'fsp')->textInput() ?>

    <?= $form->field($model, 'concepto_prima')->textInput() ?>

    <?= $form->field($model, 'concepto_cesantias')->textInput() ?>

    <?= $form->field($model, 'intereses')->textInput() ?>

    <?= $form->field($model, 'fecha_creacion')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
