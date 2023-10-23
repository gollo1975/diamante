<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NotaCredito */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nota-credito-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'numero_nota_credito')->textInput() ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <?= $form->field($model, 'nit_cedula')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cliente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_motivo')->textInput() ?>

    <?= $form->field($model, 'cufe_factura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_factura')->textInput() ?>

    <?= $form->field($model, 'id_tipo_factura')->textInput() ?>

    <?= $form->field($model, 'fecha_factura')->textInput() ?>

    <?= $form->field($model, 'fecha_nota_credito')->textInput() ?>

    <?= $form->field($model, 'fecha_enviada')->textInput() ?>

    <?= $form->field($model, 'valor_devolucion')->textInput() ?>

    <?= $form->field($model, 'valor_bruto')->textInput() ?>

    <?= $form->field($model, 'impuesto')->textInput() ?>

    <?= $form->field($model, 'retencion')->textInput() ?>

    <?= $form->field($model, 'rete_iva')->textInput() ?>

    <?= $form->field($model, 'valor_total_devolucion')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'autorizado')->textInput() ?>

    <?= $form->field($model, 'cerrar_nota')->textInput() ?>

    <?= $form->field($model, 'nuevo_saldo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
