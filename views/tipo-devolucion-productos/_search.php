<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoDevolucionProductosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-devolucion-productos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_tipo_devolucion') ?>

    <?= $form->field($model, 'concepto') ?>

    <?= $form->field($model, 'user_name') ?>

    <?= $form->field($model, 'fecha_registro') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
