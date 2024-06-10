<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CierreCajaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cierre-caja-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_cierre') ?>

    <?= $form->field($model, 'id_punto') ?>

    <?= $form->field($model, 'fecha_inicio') ?>

    <?= $form->field($model, 'fecha_corte') ?>

    <?= $form->field($model, 'total_remision') ?>

    <?php // echo $form->field($model, 'total_factura') ?>

    <?php // echo $form->field($model, 'total_efectivo_factura') ?>

    <?php // echo $form->field($model, 'total_efectivo_remision') ?>

    <?php // echo $form->field($model, 'total_transacion_factura') ?>

    <?php // echo $form->field($model, 'total_transacion_remision') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'fecha_hora_registro') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
