<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoPago */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupo-pago-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'grupo_pago')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_departamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_sucursal')->textInput() ?>

    <?= $form->field($model, 'ultimo_pago_nomina')->textInput() ?>

    <?= $form->field($model, 'ultimo_pago_prima')->textInput() ?>

    <?= $form->field($model, 'ultimo_pago_cesantia')->textInput() ?>

    <?= $form->field($model, 'limite_devengado')->textInput() ?>

    <?= $form->field($model, 'dias_pago')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_hora_registro')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
