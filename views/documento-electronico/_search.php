<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentoElectronicoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="documento-electronico-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_documento') ?>

    <?= $form->field($model, 'concepto') ?>

    <?= $form->field($model, 'sigla') ?>

    <?= $form->field($model, 'fecha_creacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
