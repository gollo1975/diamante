<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CierreCaja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cierre-caja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_punto')->textInput() ?>

    <?= $form->field($model, 'fecha_inicio')->textInput() ?>

    <?= $form->field($model, 'fecha_corte')->textInput() ?>

    <?= $form->field($model, 'total_remision')->textInput() ?>

    <?= $form->field($model, 'total_factura')->textInput() ?>

    <?= $form->field($model, 'total_efectivo_factura')->textInput() ?>

    <?= $form->field($model, 'total_efectivo_remision')->textInput() ?>

    <?= $form->field($model, 'total_transacion_factura')->textInput() ?>

    <?= $form->field($model, 'total_transacion_remision')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_hora_registro')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
