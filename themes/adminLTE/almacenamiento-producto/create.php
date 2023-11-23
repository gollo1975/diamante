<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AlmacenamientoProducto */

$this->title = 'Create Almacenamiento Producto';
$this->params['breadcrumbs'][] = ['label' => 'Almacenamiento Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="almacenamiento-producto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
