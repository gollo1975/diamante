<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CreditoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="credito-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_credito') ?>

    <?= $form->field($model, 'id_empleado') ?>

    <?= $form->field($model, 'id_grupo_pago') ?>

    <?= $form->field($model, 'codigo_credito') ?>

    <?= $form->field($model, 'id_tipo_pago') ?>

    <?php // echo $form->field($model, 'valor_credito') ?>

    <?php // echo $form->field($model, 'valor_cuota') ?>

    <?php // echo $form->field($model, 'numero_cuotas') ?>

    <?php // echo $form->field($model, 'numero_cuota_actual') ?>

    <?php // echo $form->field($model, 'validar_cuotas') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'fecha_inicio') ?>

    <?php // echo $form->field($model, 'seguro') ?>

    <?php // echo $form->field($model, 'numero_libranza') ?>

    <?php // echo $form->field($model, 'saldo_credito') ?>

    <?php // echo $form->field($model, 'estado_credito') ?>

    <?php // echo $form->field($model, 'estado_periodo') ?>

    <?php // echo $form->field($model, 'aplicar_prima') ?>

    <?php // echo $form->field($model, 'valor_aplicar') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
