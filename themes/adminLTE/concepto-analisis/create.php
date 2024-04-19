<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConceptoAnalisis */

$this->title = 'Nuevo concepto';
$this->params['breadcrumbs'][] = ['label' => 'Concepto Analisis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="concepto-analisis-create">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
