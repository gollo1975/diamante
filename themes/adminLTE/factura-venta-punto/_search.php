<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FacturaVentaPuntoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="factura-venta-punto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    
    <?php ActiveForm::end(); ?>

</div>
