<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaSolicitudKits */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entrega-solicitud-kits-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_solicitud')->textInput() ?>

    <?= $form->field($model, 'id_presentacion')->textInput() ?>

    <?= $form->field($model, 'id_solicitud_armado')->textInput() ?>

    <?= $form->field($model, 'total_unidades_entregadas')->textInput() ?>

    <?= $form->field($model, 'fecha_solicitud')->textInput() ?>

    <?= $form->field($model, 'fecha_hora_proceso')->textInput() ?>

    <?= $form->field($model, 'proceso_cerrado')->textInput() ?>

    <?= $form->field($model, 'autorizado')->textInput() ?>

    <?= $form->field($model, 'numero_entrega')->textInput() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cantidad_despachada')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
