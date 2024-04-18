<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenEnsambleProducto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orden-ensamble-producto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_orden_produccion')->textInput() ?>

    <?= $form->field($model, 'numero_orden_ensamble')->textInput() ?>

    <?= $form->field($model, 'id_grupo')->textInput() ?>

    <?= $form->field($model, 'numero_lote')->textInput() ?>

    <?= $form->field($model, 'id_etapa')->textInput() ?>

    <?= $form->field($model, 'fecha_proceso')->textInput() ?>

    <?= $form->field($model, 'fecha_hora_registro')->textInput() ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'peso_neto')->textInput() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'responsable')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
