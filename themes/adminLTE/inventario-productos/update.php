<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InventarioProductos */

$this->title = 'Actualizar: ' . $model->nombre_producto;
$this->params['breadcrumbs'][] = ['label' => 'Inventario Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_inventario, 'url' => ['update', 'id' => $model->id_inventario]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="inventario-productos-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'IdToken' => $IdToken,
    ]) ?>

</div>
