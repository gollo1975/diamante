<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReciboCajaPuntoVenta */

$this->title = 'Update Recibo Caja Punto Venta: ' . $model->id_recibo;
$this->params['breadcrumbs'][] = ['label' => 'Recibo Caja Punto Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_recibo, 'url' => ['view', 'id' => $model->id_recibo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="recibo-caja-punto-venta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
