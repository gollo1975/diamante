<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InventarioProductosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventario-productos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_inventario') ?>

    <?= $form->field($model, 'codigo_producto') ?>

    <?= $form->field($model, 'nombre_producto') ?>

    <?= $form->field($model, 'descripcion_producto') ?>

    <?= $form->field($model, 'costo_unitario') ?>

    <?php // echo $form->field($model, 'unidades_entradas') ?>

    <?php // echo $form->field($model, 'stock_unidades') ?>

    <?php // echo $form->field($model, 'id_medida_producto') ?>

    <?php // echo $form->field($model, 'id_detalle') ?>

    <?php // echo $form->field($model, 'aplica_iva') ?>

    <?php // echo $form->field($model, 'inventario_inicial') ?>

    <?php // echo $form->field($model, 'aplica_inventario') ?>

    <?php // echo $form->field($model, 'porcentaje_iva') ?>

    <?php // echo $form->field($model, 'subtotal') ?>

    <?php // echo $form->field($model, 'valor_iva') ?>

    <?php // echo $form->field($model, 'total_inventario') ?>

    <?php // echo $form->field($model, 'precio_venta_uno') ?>

    <?php // echo $form->field($model, 'precio_venta_dos') ?>

    <?php // echo $form->field($model, 'precio_venta_tres') ?>

    <?php // echo $form->field($model, 'fecha_vencimiento') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'fecha_proceso') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'codigo_ean') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
