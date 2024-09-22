<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoPagoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupo-pago-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_grupo_pago') ?>

    <?= $form->field($model, 'grupo_pago') ?>

    <?= $form->field($model, 'codigo_departamento') ?>

    <?= $form->field($model, 'codigo_municipio') ?>

    <?= $form->field($model, 'id_sucursal') ?>

    <?php // echo $form->field($model, 'ultimo_pago_nomina') ?>

    <?php // echo $form->field($model, 'ultimo_pago_prima') ?>

    <?php // echo $form->field($model, 'ultimo_pago_cesantia') ?>

    <?php // echo $form->field($model, 'limite_devengado') ?>

    <?php // echo $form->field($model, 'dias_pago') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'fecha_hora_registro') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
