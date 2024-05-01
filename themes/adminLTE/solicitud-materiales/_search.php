<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SolicitudMaterialesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="solicitud-materiales-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'codigo') ?>

    <?= $form->field($model, 'id_orden_produccion') ?>

    <?= $form->field($model, 'id_solicitud') ?>

    <?= $form->field($model, 'unidades') ?>

    <?= $form->field($model, 'numero_lote') ?>

    <?php // echo $form->field($model, 'numero_orden_produccion') ?>

    <?php // echo $form->field($model, 'fecha_hora_cierre') ?>

    <?php // echo $form->field($model, 'fecha_hora_registro') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
