<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CentroTrabajo */

$this->title = 'Create Centro Trabajo';
$this->params['breadcrumbs'][] = ['label' => 'Centro Trabajos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centro-trabajo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
