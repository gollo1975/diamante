<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TipoCotizanteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipo-cotizante-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => [ 'tag' => false,]
                ],
    ]);
    ?>
   <?php ActiveForm::end(); ?>

   
</div>