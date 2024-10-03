<?php

use yii\helpers\Html;

/* @var $this yii\web\View */ 
/* @var $model app\models\PagoAdicionalPermanente */

$this->title = 'ACTUALIZAR: ' . $model->numero_incapacidad;
$this->params['breadcrumbs'][] = ['label' => 'Incapacidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_incapacidad, 'url' => ['view', 'id' => $model->id_incapacidad]];
$this->params['breadcrumbs'][] = 'Editar';
?>

<div class="incapacidad-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    
 <?= $this->render('form', [
        'model' => $model,
    ]) ?>

</div>
