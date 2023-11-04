<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DevolucionProductos */

$this->title = 'Update Devolucion Productos: ' . $model->id_devolucion;
$this->params['breadcrumbs'][] = ['label' => 'Devolucion Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_devolucion, 'url' => ['view', 'id' => $model->id_devolucion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="devolucion-productos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
