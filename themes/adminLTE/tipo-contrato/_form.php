<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoContrato */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-contrato-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'contrato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prorroga')->textInput() ?>

    <?= $form->field($model, 'numero_prorrogas')->textInput() ?>

    <?= $form->field($model, 'prefijo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_configuracion_prefijo')->textInput() ?>

    <?= $form->field($model, 'abreviatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
