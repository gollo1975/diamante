<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionLicencia */

$this->title = 'Configuracion Licencia: ' . $model->codigo_licencia;
$this->params['breadcrumbs'][] = ['label' => 'Configuracion Licencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo_licencia, 'url' => ['view', 'id' => $model->codigo_licencia]];
$this->params['breadcrumbs'][] = 'Editar Configuración';
?>
<div class="configuracion-licencia-update">

   <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
