<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FacturaVenta */

$this->title = 'Create Factura Venta';
$this->params['breadcrumbs'][] = ['label' => 'Factura Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="factura-venta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
