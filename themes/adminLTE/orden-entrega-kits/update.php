<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenEntregaKits */

$this->title = 'Update Orden Entrega Kits: ' . $model->id_orden_entrega;
$this->params['breadcrumbs'][] = ['label' => 'Orden Entrega Kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_orden_entrega, 'url' => ['view', 'id' => $model->id_orden_entrega]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orden-entrega-kits-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
