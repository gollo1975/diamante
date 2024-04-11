<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntradaMateriaPrima */

$this->title = 'Actualizar: ' . $model->ordenCompra->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Entrada Materia Primas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entrada, 'url' => ['update', 'id' => $model->id_entrada]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="entrada-materia-prima-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form_editar', [
        'model' => $model,
        'orden_compra' => $orden_compra,
    ]) ?>

</div>
