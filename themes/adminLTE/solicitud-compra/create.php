<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SolicitudCompra */

$this->title = 'Nuevo';
$this->params['breadcrumbs'][] = ['label' => 'Solicitud Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitud-compra-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
       
    ]) ?>

</div>
