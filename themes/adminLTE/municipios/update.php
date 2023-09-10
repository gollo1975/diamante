<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Municipios */

$this->title = 'Actualizar: ' . $model->municipio;
$this->params['breadcrumbs'][] = ['label' => 'Municipios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo_municipio, 'url' => ['view', 'id' => $model->codigo_municipio]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="municipios-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
         'sw' => $sw,
    ]) ?>

</div>
