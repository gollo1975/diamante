<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TipoContrato */

$this->title = 'NUEVO';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-documento-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => 0,
    ]) ?>

</div>
