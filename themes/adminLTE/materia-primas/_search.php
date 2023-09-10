<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MateriaPrimasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="materia-primas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_materia_prima') ?>

    <?= $form->field($model, 'codigo_materia_prima') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'id_medida') ?>

    <?= $form->field($model, 'valor_unidad') ?>

    <?php // echo $form->field($model, 'aplica_iva') ?>

    <?php // echo $form->field($model, 'porcentaje_iva') ?>

    <?php // echo $form->field($model, 'valor_iva') ?>

    <?php // echo $form->field($model, 'total_cantidad') ?>

    <?php // echo $form->field($model, 'total_materia_prima') ?>

    <?php // echo $form->field($model, 'fecha_entrada') ?>

    <?php // echo $form->field($model, 'fecha_vencimiento') ?>

    <?php // echo $form->field($model, 'fecha_registro') ?>

    <?php // echo $form->field($model, 'usuario_creador') ?>

    <?php // echo $form->field($model, 'usuario_editado') ?>

    <?php // echo $form->field($model, 'aplica_inventario') ?>

    <?php // echo $form->field($model, 'entrada_salida') ?>

    <?php // echo $form->field($model, 'codigo_ean') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
