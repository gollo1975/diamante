<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MedidaProductoTerminado */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Medida Producto Terminados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medida-producto-terminado-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
