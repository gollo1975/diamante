<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AuditoriaComprasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auditoria-compras-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_auditoria') ?>

    <?= $form->field($model, 'id_orden_compra') ?>

    <?= $form->field($model, 'id_tipo_orden') ?>

    <?= $form->field($model, 'id_proveedor') ?>

    <?= $form->field($model, 'fecha_proceso_compra') ?>

    <?php // echo $form->field($model, 'numero_orden') ?>

    <?php // echo $form->field($model, 'cerrar_auditoria') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
