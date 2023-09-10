<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\IndicadorComercialSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="indicador-comercial-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_indicador') ?>

    <?= $form->field($model, 'fecha_inicio') ?>

    <?= $form->field($model, 'fecha_cierre') ?>

    <?= $form->field($model, 'anocierre') ?>

    <?= $form->field($model, 'fecha_registro') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'total_citas') ?>

    <?php // echo $form->field($model, 'total_citas_reales') ?>

    <?php // echo $form->field($model, 'total_citas_no_reales') ?>

    <?php // echo $form->field($model, 'total_porcentaje') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
