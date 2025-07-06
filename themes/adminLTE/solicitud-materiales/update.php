<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SolicitudMateriales */

$this->title = 'Actualizar: ' . $model->solicitud->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Solicitud Materiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo, 'url' => ['update', 'id' => $model->codigo]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="solicitud-materiales-update">

  <!--  <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        
        'ordenProduccion' => $ordenProduccion,
      
         'sw' =>  $sw,
    ]) ?>

</div>
