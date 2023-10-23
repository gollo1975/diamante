<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NotaCreditoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nota-credito-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_nota') ?>

    <?= $form->field($model, 'numero_nota_credito') ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'nit_cedula') ?>

    <?= $form->field($model, 'cliente') ?>

    <?php // echo $form->field($model, 'id_motivo') ?>

    <?php // echo $form->field($model, 'cufe_factura') ?>

    <?php // echo $form->field($model, 'id_factura') ?>

    <?php // echo $form->field($model, 'id_tipo_factura') ?>

    <?php // echo $form->field($model, 'fecha_factura') ?>

    <?php // echo $form->field($model, 'fecha_nota_credito') ?>

    <?php // echo $form->field($model, 'fecha_enviada') ?>

    <?php // echo $form->field($model, 'valor_devolucion') ?>

    <?php // echo $form->field($model, 'valor_bruto') ?>

    <?php // echo $form->field($model, 'impuesto') ?>

    <?php // echo $form->field($model, 'retencion') ?>

    <?php // echo $form->field($model, 'rete_iva') ?>

    <?php // echo $form->field($model, 'valor_total_devolucion') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'autorizado') ?>

    <?php // echo $form->field($model, 'cerrar_nota') ?>

    <?php // echo $form->field($model, 'nuevo_saldo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
