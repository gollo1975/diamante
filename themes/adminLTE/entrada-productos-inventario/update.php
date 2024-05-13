<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntradaProductosInventario */

$this->title = 'Actualizar: ' . $model->id_entrada;
$this->params['breadcrumbs'][] = ['label' => 'Entrada Productos Inventarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_entrada, 'url' => ['update', 'id' => $model->id_entrada, 'sw' => $sw]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="entrada-productos-inventario-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

  <?php if($sw == 0){?>
        <?= $this->render('_form_editar', [
            'model' => $model,
            'ordenes' => $ordenes,
        ]) ?>
    <?php }else{ ?>
        <?= $this->render('_form_sinorden', [
            'model' => $model,
        ]) ?>
    <?php }?>

</div>
