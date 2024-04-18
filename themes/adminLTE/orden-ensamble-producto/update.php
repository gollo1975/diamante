<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenEnsambleProducto */

$this->title = 'Update Orden Ensamble Producto: ' . $model->id_ensamble;
$this->params['breadcrumbs'][] = ['label' => 'Orden Ensamble Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_ensamble, 'url' => ['view', 'id' => $model->id_ensamble]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orden-ensamble-producto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
