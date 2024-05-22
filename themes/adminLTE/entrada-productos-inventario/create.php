<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntradaProductosInventario */

$this->title = 'NUEVA ENTRADA';
$this->params['breadcrumbs'][] = ['label' => 'Entrada Productos Inventarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entrada-productos-inventario-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

   <?php if($sw == 0){ ?>
        <?= $this->render('_form', [
            'model' => $model,
            'ordenes' => $ordenes,
        ])?>
    <?php }else{?> 
        <?= $this->render('_form_sinorden', [
            'model' => $model,
        ])?>
    <?php }?>            

</div>
