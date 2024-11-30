<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transportadora */

$this->title = 'Update Transportadora: ' . $model->id_transportadora;
$this->params['breadcrumbs'][] = ['label' => 'Transportadoras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_transportadora, 'url' => ['view', 'id' => $model->id_transportadora]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transportadora-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
