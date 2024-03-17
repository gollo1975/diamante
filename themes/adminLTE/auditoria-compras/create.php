<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuditoriaCompras */

$this->title = 'Create Auditoria Compras';
$this->params['breadcrumbs'][] = ['label' => 'Auditoria Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auditoria-compras-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
