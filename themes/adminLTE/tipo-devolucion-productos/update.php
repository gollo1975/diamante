<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoDevolucionProductos */

$this->title = 'Actualizar: ' . $model->concepto;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Devolucion Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tipo_devolucion, 'url' => ['update', 'id' => $model->id_tipo_devolucion]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="tipo-devolucion-productos-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
