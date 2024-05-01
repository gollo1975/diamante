<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaMateriales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entrega-materiales-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigo')->textInput() ?>

    <?= $form->field($model, 'numero_entrega')->textInput() ?>

    <?= $form->field($model, 'unidades_solicitadas')->textInput() ?>

    <?= $form->field($model, 'fecha_despacho')->textInput() ?>

    <?= $form->field($model, 'fecha_hora_registro')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'autorizado')->textInput() ?>

    <?= $form->field($model, 'cerrar_solicitud')->textInput() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
