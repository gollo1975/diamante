<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConceptoAnalisis */

$this->title = 'Update Concepto Analisis: ' . $model->id_analisis;
$this->params['breadcrumbs'][] = ['label' => 'Concepto Analises', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_analisis, 'url' => ['view', 'id' => $model->id_analisis]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="concepto-analisis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
