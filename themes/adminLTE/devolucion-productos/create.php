<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DevolucionProductos */

$this->title = 'Create Devolucion Productos';
$this->params['breadcrumbs'][] = ['label' => 'Devolucion Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucion-productos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
