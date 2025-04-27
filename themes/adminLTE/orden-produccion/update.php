<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenProduccion */

$this->title = 'Actualizar: ' . $model->tipoOrden;
$this->params['breadcrumbs'][] = ['label' => 'Orden Produccion', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_orden_produccion, 'url' => ['update', 'id' => $model->id_orden_produccion]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="orden-produccion-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
        'token' => $token,
        'ConProducto' => $ConProducto,
    ]) ?>

</div>
