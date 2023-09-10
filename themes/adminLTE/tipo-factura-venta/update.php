<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoFacturaVenta */

$this->title = 'Actualizar: ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Factura Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tipo_factura, 'url' => ['update', 'id' => $model->id_tipo_factura]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="tipo-factura-venta-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
