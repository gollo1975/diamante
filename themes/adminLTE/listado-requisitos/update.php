<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ListadoRequisitos */

$this->title = 'Actualizar: ' . $model->concepto;
$this->params['breadcrumbs'][] = ['label' => 'Listado Requisitos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_requisito, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="listado-requisitos-update">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
