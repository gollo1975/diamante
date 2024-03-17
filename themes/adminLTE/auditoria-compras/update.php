<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuditoriaCompras */

$this->title = 'Update Auditoria Compras: ' . $model->id_auditoria;
$this->params['breadcrumbs'][] = ['label' => 'Auditoria Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_auditoria, 'url' => ['view', 'id' => $model->id_auditoria]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auditoria-compras-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
