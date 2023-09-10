<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoDocumentoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-documento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_tipo_documento') ?>

    <?= $form->field($model, 'tipo_documento') ?>

    <?= $form->field($model, 'documento') ?>

    <?= $form->field($model, 'proceso_nomina') ?>

    <?= $form->field($model, 'proceso_cliente') ?>

    <?php // echo $form->field($model, 'proceso_proveedor') ?>

    <?php // echo $form->field($model, 'codigo_interfaz') ?>

    <?php // echo $form->field($model, 'fecha_registro') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
