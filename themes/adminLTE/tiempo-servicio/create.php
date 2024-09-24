<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TipoContrato */

$this->title = 'NUEVO';
$this->params['breadcrumbs'][] = ['label' => 'Tiempo servicio', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tiempo-servicio-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
