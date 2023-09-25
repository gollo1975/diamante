<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FacturaVenta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="factura-venta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_pedido')->textInput() ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <?= $form->field($model, 'id_tipo_factura')->textInput() ?>

    <?= $form->field($model, 'numero_factura')->textInput() ?>

    <?= $form->field($model, 'nit_cedula')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dv')->textInput() ?>

    <?= $form->field($model, 'cliente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numero_resolucion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desde')->textInput() ?>

    <?= $form->field($model, 'hasta')->textInput() ?>

    <?= $form->field($model, 'consecutivo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_inicio')->textInput() ?>

    <?= $form->field($model, 'fecha_vencimiento')->textInput() ?>

    <?= $form->field($model, 'fecha_generada')->textInput() ?>

    <?= $form->field($model, 'fecha_enviada')->textInput() ?>

    <?= $form->field($model, 'subtotal_factura')->textInput() ?>

    <?= $form->field($model, 'descuento')->textInput() ?>

    <?= $form->field($model, 'impuesto')->textInput() ?>

    <?= $form->field($model, 'total_factura')->textInput() ?>

    <?= $form->field($model, 'porcentaje_iva')->textInput() ?>

    <?= $form->field($model, 'porcentaje_rete_iva')->textInput() ?>

    <?= $form->field($model, 'porcentaje_rete_fuente')->textInput() ?>

    <?= $form->field($model, 'valor_retencion')->textInput() ?>

    <?= $form->field($model, 'valor_reteiva')->textInput() ?>

    <?= $form->field($model, 'porcentaje_descuento')->textInput() ?>

    <?= $form->field($model, 'saldo_factura')->textInput() ?>

    <?= $form->field($model, 'forma_pago')->textInput() ?>

    <?= $form->field($model, 'plazo_pago')->textInput() ?>

    <?= $form->field($model, 'autorizado')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
