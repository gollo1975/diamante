<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReciboCajaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recibo-caja-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_recibo') ?>

    <?= $form->field($model, 'numero_recibo') ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'id_tipo') ?>

    <?= $form->field($model, 'fecha_pago') ?>

    <?php // echo $form->field($model, 'fecha_proceso') ?>

    <?php // echo $form->field($model, 'valor_pago') ?>

    <?php // echo $form->field($model, 'autorizado') ?>

    <?php // echo $form->field($model, 'codigo_municipio') ?>

    <?php // echo $form->field($model, 'codigo_banco') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
