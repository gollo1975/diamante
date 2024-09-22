<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoContratoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-contrato-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_tipo_contrato') ?>

    <?= $form->field($model, 'contrato') ?>

    <?= $form->field($model, 'prorroga') ?>

    <?= $form->field($model, 'numero_prorrogas') ?>

    <?= $form->field($model, 'prefijo') ?>

    <?php // echo $form->field($model, 'id_configuracion_prefijo') ?>

    <?php // echo $form->field($model, 'abreviatura') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
