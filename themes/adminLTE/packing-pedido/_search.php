<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PackingPedidoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="packing-pedido-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_packing') ?>

    <?= $form->field($model, 'id_pedido') ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'nit_cedula_cliente') ?>

    <?= $form->field($model, 'cliente') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'fecha_packing') ?>

    <?php // echo $form->field($model, 'unidades_caja') ?>

    <?php // echo $form->field($model, 'numero_pedido') ?>

    <?php // echo $form->field($model, 'numero_guia') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
