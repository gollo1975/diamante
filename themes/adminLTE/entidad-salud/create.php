<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadSalud */

$this->title = 'Create Entidad Salud';
$this->params['breadcrumbs'][] = ['label' => 'Entidad Saluds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entidad-salud-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
