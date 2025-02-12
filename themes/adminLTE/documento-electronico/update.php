<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentoElectronico */

$this->title = 'Update Documento Electronico: ' . $model->id_documento;
$this->params['breadcrumbs'][] = ['label' => 'Documento Electronicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_documento, 'url' => ['view', 'id' => $model->id_documento]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="documento-electronico-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
