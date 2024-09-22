<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MotivoTerminacion */

$this->title = 'Create Motivo Terminacion';
$this->params['breadcrumbs'][] = ['label' => 'Motivo Terminacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motivo-terminacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
