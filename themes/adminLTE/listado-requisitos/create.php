<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ListadoRequisitos */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Listado Requisitos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listado-requisitos-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
