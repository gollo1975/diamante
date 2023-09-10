<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoOrdenCompra */

$this->title = 'Actualizar: ' . $model->descripcion_orden;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Orden Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tipo_orden, 'url' => ['update', 'id' => $model->id_tipo_orden]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="tipo-orden-compra-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
