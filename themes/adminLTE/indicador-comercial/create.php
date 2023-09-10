<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IndicadorComercial */

$this->title = 'Create Indicador Comercial';
$this->params['breadcrumbs'][] = ['label' => 'Indicador Comercials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicador-comercial-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
