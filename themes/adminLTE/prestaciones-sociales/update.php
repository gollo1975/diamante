<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrestacionesSociales */

$this->title = 'Actualizar: ' . $table->codigoSalario->nombre_concepto;
$this->params['breadcrumbs'][] = ['label' => 'Prestaciones Sociales', 'url' => ['view','id' => $id, 'pagina' => $pagina]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="prestaciones-sociales-update">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form_adicion', [
        'model' => $model,
        'tipo_adicion' => $tipo_adicion,
        'id' => $id,
        'pagina' => $pagina,
    ]) ?>

</div>
