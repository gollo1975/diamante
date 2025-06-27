<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaSolicitudKitsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entrega-solicitud-kits-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_entrega_kits') ?>

    <?= $form->field($model, 'id_solicitud') ?>

    <?= $form->field($model, 'id_presentacion') ?>

    <?= $form->field($model, 'id_solicitud_armado') ?>

    <?= $form->field($model, 'total_unidades_entregadas') ?>

    <?php // echo $form->field($model, 'fecha_solicitud') ?>

    <?php // echo $form->field($model, 'fecha_hora_proceso') ?>

    <?php // echo $form->field($model, 'proceso_cerrado') ?>

    <?php // echo $form->field($model, 'autorizado') ?>

    <?php // echo $form->field($model, 'numero_entrega') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'cantidad_despachada') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
