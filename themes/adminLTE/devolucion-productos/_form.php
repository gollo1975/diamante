<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DevolucionProductos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="devolucion-productos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <?= $form->field($model, 'id_nota')->textInput() ?>

    <?= $form->field($model, 'fecha_devolucion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cantidad')->textInput() ?>

    <?= $form->field($model, 'fecha_registro')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
