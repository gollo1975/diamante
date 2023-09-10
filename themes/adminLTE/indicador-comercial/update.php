<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IndicadorComercial */

$this->title = 'Update Indicador Comercial: ' . $model->id_indicador;
$this->params['breadcrumbs'][] = ['label' => 'Indicador Comercials', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_indicador, 'url' => ['view', 'id' => $model->id_indicador]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="indicador-comercial-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
