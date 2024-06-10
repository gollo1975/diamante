<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReciboCajaPuntoVenta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recibo-caja-punto-venta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_remision')->textInput() ?>

    <?= $form->field($model, 'id_factura')->textInput() ?>

    <?= $form->field($model, 'id_tipo')->textInput() ?>

    <?= $form->field($model, 'id_punto')->textInput() ?>

    <?= $form->field($model, 'fecha_recibo')->textInput() ?>

    <?= $form->field($model, 'numero_recibo')->textInput() ?>

    <?= $form->field($model, 'valor_abono')->textInput() ?>

    <?= $form->field($model, 'valor_saldo')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
