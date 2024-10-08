<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProgramacionNominaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="programacion-nomina-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_programacion') ?>

    <?= $form->field($model, 'id_grupo_pago') ?>

    <?= $form->field($model, 'id_periodo_pago_nomina') ?>

    <?= $form->field($model, 'id_tipo_nomina') ?>

    <?= $form->field($model, 'id_contrato') ?>

    <?php // echo $form->field($model, 'id_empleado') ?>

    <?php // echo $form->field($model, 'cedula_empleado') ?>

    <?php // echo $form->field($model, 'salario_contrato') ?>

    <?php // echo $form->field($model, 'fecha_inicio_contrato') ?>

    <?php // echo $form->field($model, 'fecha_final_contrato') ?>

    <?php // echo $form->field($model, 'fecha_ultima_prima') ?>

    <?php // echo $form->field($model, 'fecha_ultima_cesantia') ?>

    <?php // echo $form->field($model, 'fecha_ultima_vacacion') ?>

    <?php // echo $form->field($model, 'fecha_desde') ?>

    <?php // echo $form->field($model, 'nro_pago') ?>

    <?php // echo $form->field($model, 'total_devengado') ?>

    <?php // echo $form->field($model, 'total_pagar') ?>

    <?php // echo $form->field($model, 'total_deduccion') ?>

    <?php // echo $form->field($model, 'total_auxilio_transporte') ?>

    <?php // echo $form->field($model, 'ibc_prestacional') ?>

    <?php // echo $form->field($model, 'vlr_ibp_medio_tiempo') ?>

    <?php // echo $form->field($model, 'ibc_no_prestacional') ?>

    <?php // echo $form->field($model, 'total_licencia') ?>

    <?php // echo $form->field($model, 'total_incapacidad') ?>

    <?php // echo $form->field($model, 'ajuste_incapacidad') ?>

    <?php // echo $form->field($model, 'total_tiempo_extra') ?>

    <?php // echo $form->field($model, 'total_recargo') ?>

    <?php // echo $form->field($model, 'fecha_hasta') ?>

    <?php // echo $form->field($model, 'fecha_real_corte') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'fecha_inicio_vacacion') ?>

    <?php // echo $form->field($model, 'fecha_final_vacacion') ?>

    <?php // echo $form->field($model, 'dias_vacacion') ?>

    <?php // echo $form->field($model, 'horas_vacacion') ?>

    <?php // echo $form->field($model, 'ibc_vacacion') ?>

    <?php // echo $form->field($model, 'dias_pago') ?>

    <?php // echo $form->field($model, 'dia_real_pagado') ?>

    <?php // echo $form->field($model, 'horas_pago') ?>

    <?php // echo $form->field($model, 'estado_generado') ?>

    <?php // echo $form->field($model, 'estado_liquidado') ?>

    <?php // echo $form->field($model, 'estado_cerrado') ?>

    <?php // echo $form->field($model, 'factor_dia') ?>

    <?php // echo $form->field($model, 'salario_medio_tiempo') ?>

    <?php // echo $form->field($model, 'salario_promedio') ?>

    <?php // echo $form->field($model, 'dias_ausentes') ?>

    <?php // echo $form->field($model, 'total_ibc_no_prestacional') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'importar_prima') ?>

    <?php // echo $form->field($model, 'pago_aplicado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
