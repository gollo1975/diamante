<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FacturaVentaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="factura-venta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_factura') ?>

    <?= $form->field($model, 'id_pedido') ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'id_tipo_factura') ?>

    <?= $form->field($model, 'numero_factura') ?>

    <?php // echo $form->field($model, 'nit_cedula') ?>

    <?php // echo $form->field($model, 'dv') ?>

    <?php // echo $form->field($model, 'cliente') ?>

    <?php // echo $form->field($model, 'numero_resolucion') ?>

    <?php // echo $form->field($model, 'desde') ?>

    <?php // echo $form->field($model, 'hasta') ?>

    <?php // echo $form->field($model, 'consecutivo') ?>

    <?php // echo $form->field($model, 'fecha_inicio') ?>

    <?php // echo $form->field($model, 'fecha_vencimiento') ?>

    <?php // echo $form->field($model, 'fecha_generada') ?>

    <?php // echo $form->field($model, 'fecha_enviada') ?>

    <?php // echo $form->field($model, 'subtotal_factura') ?>

    <?php // echo $form->field($model, 'descuento') ?>

    <?php // echo $form->field($model, 'impuesto') ?>

    <?php // echo $form->field($model, 'total_factura') ?>

    <?php // echo $form->field($model, 'porcentaje_iva') ?>

    <?php // echo $form->field($model, 'porcentaje_rete_iva') ?>

    <?php // echo $form->field($model, 'porcentaje_rete_fuente') ?>

    <?php // echo $form->field($model, 'valor_retencion') ?>

    <?php // echo $form->field($model, 'valor_reteiva') ?>

    <?php // echo $form->field($model, 'porcentaje_descuento') ?>

    <?php // echo $form->field($model, 'saldo_factura') ?>

    <?php // echo $form->field($model, 'forma_pago') ?>

    <?php // echo $form->field($model, 'plazo_pago') ?>

    <?php // echo $form->field($model, 'autorizado') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
