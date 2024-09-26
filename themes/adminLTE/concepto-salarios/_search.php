<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConceptoSalariosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="concepto-salarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'codigo_salario') ?>

    <?= $form->field($model, 'nombre_concepto') ?>

    <?= $form->field($model, 'compone_salario') ?>

    <?= $form->field($model, 'inicio_nomina') ?>

    <?= $form->field($model, 'aplica_porcentaje') ?>

    <?php // echo $form->field($model, 'porcentaje') ?>

    <?php // echo $form->field($model, 'porcentaje_tiempo_extra') ?>

    <?php // echo $form->field($model, 'prestacional') ?>

    <?php // echo $form->field($model, 'ingreso_base_prestacional') ?>

    <?php // echo $form->field($model, 'ingreso_base_cotizacion') ?>

    <?php // echo $form->field($model, 'debito_credito') ?>

    <?php // echo $form->field($model, 'adicion') ?>

    <?php // echo $form->field($model, 'auxilio_transporte') ?>

    <?php // echo $form->field($model, 'concepto_incapacidad') ?>

    <?php // echo $form->field($model, 'concepto_pension') ?>

    <?php // echo $form->field($model, 'concepto_salud') ?>

    <?php // echo $form->field($model, 'concepto_vacacion') ?>

    <?php // echo $form->field($model, 'provisiona_vacacion') ?>

    <?php // echo $form->field($model, 'provisiona_indemnizacion') ?>

    <?php // echo $form->field($model, 'tipo_adicion') ?>

    <?php // echo $form->field($model, 'recargo_nocturno') ?>

    <?php // echo $form->field($model, 'hora_extra') ?>

    <?php // echo $form->field($model, 'concepto_comision') ?>

    <?php // echo $form->field($model, 'concepto_licencia') ?>

    <?php // echo $form->field($model, 'fsp') ?>

    <?php // echo $form->field($model, 'concepto_prima') ?>

    <?php // echo $form->field($model, 'concepto_cesantias') ?>

    <?php // echo $form->field($model, 'intereses') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
