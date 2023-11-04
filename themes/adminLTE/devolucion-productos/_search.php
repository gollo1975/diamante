<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DevolucionProductosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="devolucion-productos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_devolucion') ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'id_nota') ?>

    <?= $form->field($model, 'fecha_devolucion') ?>

    <?= $form->field($model, 'cantidad') ?>

    <?php // echo $form->field($model, 'fecha_registro') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
