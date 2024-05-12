<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntradaProductosInventarioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entrada-productos-inventario-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_entrada') ?>

    <?= $form->field($model, 'id_proveedor') ?>

    <?= $form->field($model, 'id_orden_compra') ?>

    <?= $form->field($model, 'fecha_proceso') ?>

    <?= $form->field($model, 'fecha_registro') ?>

    <?php // echo $form->field($model, 'numero_soporte') ?>

    <?php // echo $form->field($model, 'total_unidades') ?>

    <?php // echo $form->field($model, 'subtotal') ?>

    <?php // echo $form->field($model, 'impuesto') ?>

    <?php // echo $form->field($model, 'total_salida') ?>

    <?php // echo $form->field($model, 'autorizado') ?>

    <?php // echo $form->field($model, 'enviar_materia_prima') ?>

    <?php // echo $form->field($model, 'user_name_crear') ?>

    <?php // echo $form->field($model, 'user_name_edit') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'tipo_entrada') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
