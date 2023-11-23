<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AlmacenamientoProducto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="almacenamiento-producto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_orden_produccion')->textInput() ?>

    <?= $form->field($model, 'id_documento')->textInput() ?>

    <?= $form->field($model, 'id_rack')->textInput() ?>

    <?= $form->field($model, 'codigo_producto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre_producto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unidades_producidas')->textInput() ?>

    <?= $form->field($model, 'unidades_almacenadas')->textInput() ?>

    <?= $form->field($model, 'unidades_faltantes')->textInput() ?>

    <?= $form->field($model, 'fecha_almacenamiento')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
