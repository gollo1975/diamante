<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenEnsambleProducto */

$this->title = 'Create Orden Ensamble Producto';
$this->params['breadcrumbs'][] = ['label' => 'Orden Ensamble Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orden-ensamble-producto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
