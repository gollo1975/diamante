<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TiempoServicio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tiempo-servicio-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tiempo_servicio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'horas_dia')->textInput() ?>

    <?= $form->field($model, 'pago_incapacidad_general')->textInput() ?>

    <?= $form->field($model, 'pago_incapacidad_laboral')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
