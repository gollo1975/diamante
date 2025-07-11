<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenEntregaKitsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orden-entrega-kits-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_orden_entrega') ?>

    <?= $form->field($model, 'id_entrega_kits') ?>

    <?= $form->field($model, 'id_presentacion') ?>

    <?= $form->field($model, 'id_inventario') ?>

    <?= $form->field($model, 'total_kits') ?>

    <?php // echo $form->field($model, 'total_productos_procesados') ?>

    <?php // echo $form->field($model, 'fecha_orden') ?>

    <?php // echo $form->field($model, 'fecha_hora_registro') ?>

    <?php // echo $form->field($model, 'autorizado') ?>

    <?php // echo $form->field($model, 'proceso_cerrado') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
