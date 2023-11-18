<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoRack */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-rack-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'numero_rack')->textInput() ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'medida_ancho')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'media_alto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_peso')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
