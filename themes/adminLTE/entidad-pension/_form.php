<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadPension */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entidad-pension-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_entidad_pension')->textInput() ?>

    <?= $form->field($model, 'entidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
