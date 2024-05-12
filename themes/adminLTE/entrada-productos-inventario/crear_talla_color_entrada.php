<?php

//modelos
//clase
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Ordenproduccion */

$this->title = 'CREAR TALLAS Y COLORES';
$this->params['breadcrumbs'][] = ['label' => 'Entrada inventario', 'url' => ['index']];
$this->params['breadcrumbs'][] = $id;
?>
<div class="entrada-productos-inventario-color">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view', 'id' => $id, 'token' => $token], ['class' => 'btn btn-primary btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            LISTADO DE TALLAS Y COLORES
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, "codigo_producto") ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Nombre_producto') ?></th>
                    <td><?= Html::encode($model->inventario->nombre_producto) ?></td>
                </tr>
            </table>
        </div>    
    </div>
      <?php $form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
    'fieldConfig' => [
        'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
        'labelOptions' => ['class' => 'col-sm-3 control-label'],
        'options' => []
    ],
    ]);?>
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tallascolores" aria-controls="tallascolores" role="tab" data-toggle="tab">Tallas y colores <span class="badge"><?= count($listadoTallaColor) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="entrada">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 90%;">
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre de la talla</th>                        
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Nombre del color</th>                        
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Cantidad inicial</th>  
                                         <th scope="col" align="center" style='background-color:#B9D5CE;'>Existencias actuales</th>  
                                        <th scope="col" align="center" style='background-color:#B9D5CE;'>Nueva entrada</th>       
                                        <th scope="col" style='background-color:#B9D5CE;'></th> 

                                    </tr>
                                </thead>
                                <body>
                                    <?php
                                    foreach ($listadoTallaColor as $val):?>
                                        <tr style="font-size: 90%;">
                                            <td><?= $val->talla->nombre_talla?></td>
                                            <td><?= $val->color->colores?></td>
                                            <td style="text-align: right"><?= $val->cantidad?></td>
                                            <td style="text-align: right"><?= $val->stock_punto?></td>
                                            <td style="padding-right: 1; padding-right: 1; text-align: right"><input type="text" name="nueva_cantidad[]" style="text-align: right" value="" size="5" > </td> 

                                        </tr>
                                    <?php endforeach;?>
                                </body>        
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--TERMINA TABS-->
        </div>  
    </div>  
     <?php ActiveForm::end(); ?>  
</div>