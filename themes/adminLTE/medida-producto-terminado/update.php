<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MedidaProductoTerminado */

$this->title = 'Actualizar: ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Medida Producto Terminados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_medida_producto, 'url' => ['update', 'id' => $model->id_medida_producto]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="medida-producto-terminado-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
