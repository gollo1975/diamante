<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReciboCajaPuntoVenta */

$this->title = 'Create Recibo Caja Punto Venta';
$this->params['breadcrumbs'][] = ['label' => 'Recibo Caja Punto Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recibo-caja-punto-venta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
