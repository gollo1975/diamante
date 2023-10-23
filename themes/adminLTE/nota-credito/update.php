<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NotaCredito */

$this->title = 'Update Nota Credito: ' . $model->id_nota;
$this->params['breadcrumbs'][] = ['label' => 'Nota Creditos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_nota, 'url' => ['view', 'id' => $model->id_nota]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nota-credito-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
