<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AlmacenamientoProducto */

$this->title = 'Update Almacenamiento Producto: ' . $model->id_almacenamiento;
$this->params['breadcrumbs'][] = ['label' => 'Almacenamiento Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_almacenamiento, 'url' => ['view', 'id' => $model->id_almacenamiento]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="almacenamiento-producto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
