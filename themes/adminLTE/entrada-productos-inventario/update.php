<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntradaProductosInventario */

$this->title = 'Update Entrada Productos Inventario: ' . $model->id_entrada;
$this->params['breadcrumbs'][] = ['label' => 'Entrada Productos Inventarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entrada, 'url' => ['view', 'id' => $model->id_entrada]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="entrada-productos-inventario-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
