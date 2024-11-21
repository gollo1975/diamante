<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PackingPedido */

$this->title = 'Create Packing Pedido';
$this->params['breadcrumbs'][] = ['label' => 'Packing Pedidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="packing-pedido-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
