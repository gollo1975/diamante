<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ContratosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contratos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_contrato') ?>

    <?= $form->field($model, 'id_empleado') ?>

    <?= $form->field($model, 'nit_cedula') ?>

    <?= $form->field($model, 'id_tiempo') ?>

    <?= $form->field($model, 'id_tipo_contrato') ?>

    <?php // echo $form->field($model, 'id_cargo') ?>

    <?php // echo $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'fecha_inicio') ?>

    <?php // echo $form->field($model, 'fecha_final') ?>

    <?php // echo $form->field($model, 'id_tipo_salario') ?>

    <?php // echo $form->field($model, 'salario') ?>

    <?php // echo $form->field($model, 'aplica_auxilio_transporte') ?>

    <?php // echo $form->field($model, 'horario_trabajo') ?>

    <?php // echo $form->field($model, 'funciones') ?>

    <?php // echo $form->field($model, 'id_tipo_cotizante') ?>

    <?php // echo $form->field($model, 'id_subtipo_cotizante') ?>

    <?php // echo $form->field($model, 'id_configuracion_eps') ?>

    <?php // echo $form->field($model, 'id_entidad_salud') ?>

    <?php // echo $form->field($model, 'id_configuracion_pension') ?>

    <?php // echo $form->field($model, 'id_entidad_pension') ?>

    <?php // echo $form->field($model, 'id_caja_compensacion') ?>

    <?php // echo $form->field($model, 'id_cesantia') ?>

    <?php // echo $form->field($model, 'id_arl') ?>

    <?php // echo $form->field($model, 'ultimo_pago_nomina') ?>

    <?php // echo $form->field($model, 'ultima_pago_prima') ?>

    <?php // echo $form->field($model, 'ultima_pago_cesantia') ?>

    <?php // echo $form->field($model, 'ultima_pago_vacacion') ?>

    <?php // echo $form->field($model, 'ibp_cesantia_inicial') ?>

    <?php // echo $form->field($model, 'ibp_prima_inicial') ?>

    <?php // echo $form->field($model, 'ibp_recargo_nocturno') ?>

    <?php // echo $form->field($model, 'id_motivo_terminacion') ?>

    <?php // echo $form->field($model, 'contrato_activo') ?>

    <?php // echo $form->field($model, 'codigo_municipio_laboral') ?>

    <?php // echo $form->field($model, 'codigo_municipio_contratado') ?>

    <?php // echo $form->field($model, 'id_centro_trabajo') ?>

    <?php // echo $form->field($model, 'id_grupo_pago') ?>

    <?php // echo $form->field($model, 'fecha_preaviso') ?>

    <?php // echo $form->field($model, 'dias_contrato') ?>

    <?php // echo $form->field($model, 'generar_liquidacion') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'fecha_hora_registro') ?>

    <?php // echo $form->field($model, 'user_name_editado') ?>

    <?php // echo $form->field($model, 'fecha_hora_editado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
