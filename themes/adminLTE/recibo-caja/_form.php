<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReciboCaja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recibo-caja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'numero_recibo')->textInput() ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <?= $form->field($model, 'id_tipo')->textInput() ?>

    <?= $form->field($model, 'fecha_pago')->textInput() ?>

    <?= $form->field($model, 'fecha_proceso')->textInput() ?>

    <?= $form->field($model, 'valor_pago')->textInput() ?>

    <?= $form->field($model, 'autorizado')->textInput() ?>

    <?= $form->field($model, 'codigo_municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_banco')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
