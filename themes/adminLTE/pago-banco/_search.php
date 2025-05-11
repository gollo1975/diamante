<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PagoBancoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pago-banco-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pago_banco') ?>

    <?= $form->field($model, 'id_empresa') ?>

    <?= $form->field($model, 'nit_cedula') ?>

    <?= $form->field($model, 'codigo_banco') ?>

    <?= $form->field($model, 'tipo_pago') ?>

    <?php // echo $form->field($model, 'id_tipo_nomina') ?>

    <?php // echo $form->field($model, 'aplicacion') ?>

    <?php // echo $form->field($model, 'secuencia') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'fecha_aplicacion') ?>

    <?php // echo $form->field($model, 'total_empleados') ?>

    <?php // echo $form->field($model, 'total_pagar') ?>

    <?php // echo $form->field($model, 'adicion_numero') ?>

    <?php // echo $form->field($model, 'debitos') ?>

    <?php // echo $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'autorizado') ?>

    <?php // echo $form->field($model, 'cerrar_proceso') ?>

    <?php // echo $form->field($model, 'fecha_hora_registro') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
