<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadBancariasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entidad-bancarias-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'codigo_banco') ?>

    <?= $form->field($model, 'nit_banco') ?>

    <?= $form->field($model, 'dv') ?>

    <?= $form->field($model, 'entidad_bancaria') ?>

    <?= $form->field($model, 'direccion_banco') ?>

    <?php // echo $form->field($model, 'telefono_banco') ?>

    <?php // echo $form->field($model, 'tipo_producto') ?>

    <?php // echo $form->field($model, 'producto') ?>

    <?php // echo $form->field($model, 'id_empresa') ?>

    <?php // echo $form->field($model, 'convenio_nomina') ?>

    <?php // echo $form->field($model, 'convenio_proveedor') ?>

    <?php // echo $form->field($model, 'convenio_empresa') ?>

    <?php // echo $form->field($model, 'estado_registro') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'codigo_interfaz') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
