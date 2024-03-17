<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProveedorEstudiosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="proveedor-estudios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_estudio') ?>

    <?= $form->field($model, 'id_tipo_documento') ?>

    <?= $form->field($model, 'nit_cedula') ?>

    <?= $form->field($model, 'dv') ?>

    <?= $form->field($model, 'primer_nombre') ?>

    <?php // echo $form->field($model, 'segundo_nombre') ?>

    <?php // echo $form->field($model, 'primer_apellido') ?>

    <?php // echo $form->field($model, 'segundo_apellido') ?>

    <?php // echo $form->field($model, 'razon_social') ?>

    <?php // echo $form->field($model, 'nombre_completo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
