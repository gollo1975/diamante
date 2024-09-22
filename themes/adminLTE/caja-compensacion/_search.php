<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CajaCompensacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="caja-compensacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_caja') ?>

    <?= $form->field($model, 'caja') ?>

    <?= $form->field($model, 'telefono') ?>

    <?= $form->field($model, 'direccion') ?>

    <?= $form->field($model, 'codigo') ?>

    <?php // echo $form->field($model, 'codigo_municipio') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'porcentaje') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'fecha_hora_registro') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
