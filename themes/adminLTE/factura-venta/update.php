<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FacturaVenta */

$this->title = 'Update Factura Venta: ' . $model->id_factura;
$this->params['breadcrumbs'][] = ['label' => 'Factura Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_factura, 'url' => ['view', 'id' => $model->id_factura]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="factura-venta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
