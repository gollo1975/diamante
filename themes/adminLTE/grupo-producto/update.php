<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoProducto */

$this->title = 'Actualizar: ' . $model->nombre_grupo;
$this->params['breadcrumbs'][] = ['label' => 'Grupo Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_grupo, 'url' => ['update', 'id' => $model->id_grupo]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="grupo-producto-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
