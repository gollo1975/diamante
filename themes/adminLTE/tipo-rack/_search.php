<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoRackSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-rack-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_rack') ?>

    <?= $form->field($model, 'numero_rack') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'medida_ancho') ?>

    <?= $form->field($model, 'media_alto') ?>

    <?php // echo $form->field($model, 'total_peso') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
