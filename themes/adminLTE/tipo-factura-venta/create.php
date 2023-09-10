<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoFacturaVenta */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Tipo Factura Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-factura-venta-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
