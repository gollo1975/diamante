<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SolicitudCompra */

$this->title = 'Actualizar: ' . $model->solicitud->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Solicitud Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_solicitud_compra, 'url' => ['update', 'id' => $model->id_solicitud_compra]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="solicitud-compra-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
