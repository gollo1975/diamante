<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SolicitudMateriales */

$this->title = 'NUEVA SOLICITUD';
$this->params['breadcrumbs'][] = ['label' => 'Solicitud Materiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="solicitud-materiales-create">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'tipoSolicitud' => $tipoSolicitud,
        'ordenProduccion' => $ordenProduccion,
        'grupo' => $grupo,
        'sw' => $sw,
    ]) ?>

</div>
