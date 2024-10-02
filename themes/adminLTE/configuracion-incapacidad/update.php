<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionIncapacidad */

$this->title = 'Configuracion Incapacidad: ' . $model->codigo_incapacidad;
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Incapacidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo_incapacidad, 'url' => ['index', 'id' => $model->codigo_incapacidad]];
$this->params['breadcrumbs'][] = 'Editar incapacidad';
?>
<div class="configuracion-incapacidad-update">

     <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
