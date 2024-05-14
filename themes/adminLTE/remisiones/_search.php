<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RemisionesVentaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="remisiones-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_remision') ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'numero_remision') ?>

    <?= $form->field($model, 'fecha_inicio') ?>

    <?= $form->field($model, 'fecha_hora_registro') ?>

    <?php // echo $form->field($model, 'valor_bruto') ?>

    <?php // echo $form->field($model, 'descuento') ?>

    <?php // echo $form->field($model, 'subtotal') ?>

    <?php // echo $form->field($model, 'total_remision') ?>

    <?php // echo $form->field($model, 'autorizado') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'fecha_editada') ?>

    <?php // echo $form->field($model, 'user_name_editado') ?>

    <?php // echo $form->field($model, 'estado_remision') ?>

    <?php // echo $form->field($model, 'id_punto') ?>

    <?php // echo $form->field($model, 'exportar_inventario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
