<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PagoAdicionalPermanenteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pago-adicional-permanente-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pago_permanente') ?>

    <?= $form->field($model, 'id_empleado') ?>

    <?= $form->field($model, 'codigo_salario') ?>

    <?= $form->field($model, 'id_contrato') ?>

    <?= $form->field($model, 'id_grupo_pago') ?>

    <?php // echo $form->field($model, 'id_pago_fecha') ?>

    <?php // echo $form->field($model, 'fecha_corte') ?>

    <?php // echo $form->field($model, 'tipo_adicion') ?>

    <?php // echo $form->field($model, 'valor_adicion') ?>

    <?php // echo $form->field($model, 'permanente') ?>

    <?php // echo $form->field($model, 'aplicar_dia_laborado') ?>

    <?php // echo $form->field($model, 'aplicar_prima') ?>

    <?php // echo $form->field($model, 'aplicar_cesantias') ?>

    <?php // echo $form->field($model, 'estado_registro') ?>

    <?php // echo $form->field($model, 'estado_periodo') ?>

    <?php // echo $form->field($model, 'detalle') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
