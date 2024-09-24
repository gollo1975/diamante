<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SubtipoCotizante */

$this->title = 'NUEVO';
$this->params['breadcrumbs'][] = ['label' => 'Subtipos Cotizantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subtipo-cotizante-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
