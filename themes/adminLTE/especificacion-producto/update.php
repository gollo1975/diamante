<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EspecificacionProducto */

$this->title = 'Actualizar: ' . $model->concepto;
$this->params['breadcrumbs'][] = ['label' => 'Especificacion Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_especificacion, 'url' => ['update', 'id' => $model->id_especificacion]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="especificacion-producto-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
