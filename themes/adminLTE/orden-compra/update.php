<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenCompra */

$this->title = 'Actualizar: ' . $model->tipoOrden->descripcion_orden;
$this->params['breadcrumbs'][] = ['label' => 'Orden Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_orden_compra, 'url' => ['update', 'id' => $model->id_orden_compra]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="orden-compra-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
