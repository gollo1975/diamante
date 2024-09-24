<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CentroTrabajo */

$this->title = 'NUEVO';
$this->params['breadcrumbs'][] = ['label' => 'Centros de Trabajo', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centro-trabajo-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'sw' => $sw,
    ]) ?>

</div>
