<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PresentacionProducto */

$this->title = 'Actualizar: ' . $model->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Presentacion de productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_presentacion, 'url' => ['update', 'id' => $model->id_presentacion]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="presentacion-producto-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
