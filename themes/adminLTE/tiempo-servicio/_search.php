<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TiempoServicioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tiempo-servicio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_tiempo') ?>

    <?= $form->field($model, 'tiempo_servicio') ?>

    <?= $form->field($model, 'horas_dia') ?>

    <?= $form->field($model, 'pago_incapacidad_general') ?>

    <?= $form->field($model, 'pago_incapacidad_laboral') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
