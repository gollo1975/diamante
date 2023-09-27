<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FacturaVenta */

$this->title = 'Actualizar ';
$this->params['breadcrumbs'][] = ['label' => 'Factura Ventas', 'url' => ['update','id' => $model->id_factura, 'token' => $token]];
$this->params['breadcrumbs'][] = ['label' => $model->id_factura, 'url' => ['update', 'id' => $model->id_factura]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="factura-venta-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'token' => $token,
    ]) ?>

</div>
