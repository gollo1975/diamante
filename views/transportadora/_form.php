<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transportadora */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transportadora-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tipo_documento')->textInput() ?>

    <?= $form->field($model, 'nit_cedula')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dv')->textInput() ?>

    <?= $form->field($model, 'razon_social')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email_transportadora')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_departamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contacto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular_contacto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_registro')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
