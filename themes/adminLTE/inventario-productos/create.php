<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InventarioProductos */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Inventario Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventario-productos-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'IdToken' => $IdToken,
    ]) ?>

</div>
