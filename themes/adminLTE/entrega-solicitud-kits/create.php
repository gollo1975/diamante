<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntregaSolicitudKits */

$this->title = 'Create Entrega Solicitud Kits';
$this->params['breadcrumbs'][] = ['label' => 'Entrega Solicitud Kits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entrega-solicitud-kits-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
