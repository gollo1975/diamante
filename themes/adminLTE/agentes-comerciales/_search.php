<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AgentesComercialesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agentes-comerciales-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_agente') ?>

    <?= $form->field($model, 'id_tipo_documento') ?>

    <?= $form->field($model, 'documento') ?>

    <?= $form->field($model, 'primer_nombre') ?>

    <?= $form->field($model, 'segundo_nombre') ?>

    <?php // echo $form->field($model, 'primer_apellido') ?>

    <?php // echo $form->field($model, 'segundo_apellido') ?>

    <?php // echo $form->field($model, 'celular_agente') ?>

    <?php // echo $form->field($model, 'direccion') ?>

    <?php // echo $form->field($model, 'codigo_departamento') ?>

    <?php // echo $form->field($model, 'codigo_municipio') ?>

    <?php // echo $form->field($model, 'fecha_registro') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'id_cargo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
