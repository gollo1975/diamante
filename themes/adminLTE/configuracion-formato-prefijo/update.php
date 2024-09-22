<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionFormatoPrefijo */

$this->title = 'Update Configuracion Formato Prefijo: ' . $model->id_configuracion_prefijo;
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Formato Prefijos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_configuracion_prefijo, 'url' => ['view', 'id' => $model->id_configuracion_prefijo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="configuracion-formato-prefijo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
