<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntidadBancarias */

$this->title = 'Actualizar: ' . $model->entidad_bancaria;
$this->params['breadcrumbs'][] = ['label' => 'Entidad Bancarias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo_banco, 'url' => ['update', 'id' => $model->codigo_banco]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="entidad-bancarias-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_formedit', [
        'model' => $model,
        'municipio' => $municipio,
    ]) ?>

</div>
