<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SolicitudArmadoKitsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="solicitud-armado-kits-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_solicitud_armado') ?>

    <?= $form->field($model, 'id_solicitud') ?>

    <?= $form->field($model, 'id_presentacion') ?>

    <?= $form->field($model, 'total_unidades') ?>

    <?= $form->field($model, 'fecha_solicitud') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'fecha_hora_proceso') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
