<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NotaCredito */

$this->title = 'Create Nota Credito';
$this->params['breadcrumbs'][] = ['label' => 'Nota Creditos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nota-credito-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
