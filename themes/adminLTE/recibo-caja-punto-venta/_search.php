<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReciboCajaPuntoVentaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recibo-caja-punto-venta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_recibo') ?>

    <?= $form->field($model, 'id_remision') ?>

    <?= $form->field($model, 'id_factura') ?>

    <?= $form->field($model, 'id_tipo') ?>

    <?= $form->field($model, 'id_punto') ?>

    <?php // echo $form->field($model, 'fecha_recibo') ?>

    <?php // echo $form->field($model, 'numero_recibo') ?>

    <?php // echo $form->field($model, 'valor_abono') ?>

    <?php // echo $form->field($model, 'valor_saldo') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
