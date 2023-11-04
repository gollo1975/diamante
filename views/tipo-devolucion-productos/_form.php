<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoDevolucionProductos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-devolucion-productos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'concepto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_registro')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
