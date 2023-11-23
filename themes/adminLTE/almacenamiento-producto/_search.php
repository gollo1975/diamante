<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AlmacenamientoProductoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="almacenamiento-producto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_almacenamiento') ?>

    <?= $form->field($model, 'id_orden_produccion') ?>

    <?= $form->field($model, 'id_documento') ?>

    <?= $form->field($model, 'id_rack') ?>

    <?= $form->field($model, 'codigo_producto') ?>

    <?php // echo $form->field($model, 'nombre_producto') ?>

    <?php // echo $form->field($model, 'unidades_producidas') ?>

    <?php // echo $form->field($model, 'unidades_almacenadas') ?>

    <?php // echo $form->field($model, 'unidades_faltantes') ?>

    <?php // echo $form->field($model, 'fecha_almacenamiento') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
