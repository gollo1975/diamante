<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenEntregaKits */

$this->title = 'Create Orden Entrega Kits';
$this->params['breadcrumbs'][] = ['label' => 'Orden Entrega Kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orden-entrega-kits-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
