<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionCredito */

$this->title = 'Tipos de  credito: ' . $model->codigo_credito;
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Creditos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo_credito, 'url' => ['index', 'id' => $model->codigo_credito]];
$this->params['breadcrumbs'][] = 'Editar crédito';
?>
<div class="configuracion-credito-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
