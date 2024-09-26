<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FormatoContenidoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="formato-contenido-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_formato_contenido') ?>

    <?= $form->field($model, 'nombre_formato') ?>

    <?= $form->field($model, 'contenido') ?>

    <?= $form->field($model, 'id_configuracion_prefijo') ?>

    <?= $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
