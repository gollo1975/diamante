<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AuditoriaCompras */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auditoria-compras-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_orden_compra')->textInput() ?>

    <?= $form->field($model, 'id_tipo_orden')->textInput() ?>

    <?= $form->field($model, 'id_proveedor')->textInput() ?>

    <?= $form->field($model, 'fecha_proceso_compra')->textInput() ?>

    <?= $form->field($model, 'numero_orden')->textInput() ?>

    <?= $form->field($model, 'cerrar_auditoria')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
