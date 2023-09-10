<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Almacen */

$this->title = 'Actualizar: ' . $model->almacen;
$this->params['breadcrumbs'][] = ['label' => 'Almacen', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_almacen, 'url' => ['update', 'id' => $model->id_almacen]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="almacen-update">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
