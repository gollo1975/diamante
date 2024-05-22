<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Carousel;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'Vista (' .$model->nombre_producto. ')';
$this->params['breadcrumbs'][] = ['label' => 'Inventario bodega', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id_inventario;
?>
<div class="inventario-punto-venta-view">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?php if($token == 1){
            echo  Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_inventario'], ['class' => 'btn btn-primary btn-sm']);
        }else{ 
            echo Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['search_referencias'], ['class' => 'btn btn-primary btn-sm']);
        }?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            DETALLE DEL INVENTARIO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <?php if($tokenAcceso == 1){?>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_inventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'iva_incluido') ?></th>
                    <td><?= Html::encode($model->ivaIncluido) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'precio_deptal') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->precio_deptal,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_punto') ?></th>
                    <td><?= Html::encode($model->punto->nombre_punto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_marca') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->marca->marca) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_barra') ?></th>
                    <td><?= Html::encode($model->codigo_barra) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'stock_unidades') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->stock_unidades,0)) ?></td>
                   <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'stock_inventario') ?></th>
                    <td style="text-align: right; background-color:#F5EEF8;"><?= Html::encode(''.number_format($model->stock_inventario,0)) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'inventario_inicial') ?></th>
                    <td><?= Html::encode($model->inventarioInicial) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_categoria') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->categoria->categoria) ?></td>
                </tr>
                <tr style="font-size: 90%;" >
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion_producto') ?></th>
                    <td colspan="10"><?= Html::encode($model->descripcion_producto)?></td>
                </tr>
                <?php }else{?>
                    <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_inventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_punto') ?></th>
                    <td><?= Html::encode($model->punto->nombre_punto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_categoria') ?></th>
                    <td style="text-align: right;"><?= Html::encode($model->categoria->categoria) ?></td>
                </tr>
                <?php }?>
            </table>
            <!-- TERMINA TABLE-->
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
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#talla_color" aria-controls="talla_color" role="tab" data-toggle="tab">Tallas y colores <span class="badge"><?= count($talla_color) ?></span></a></li>
            <li role="presentation"><a href="#listado_entradas" aria-controls="listado_entradas" role="tab" data-toggle="tab">Entradas de inventario <span class="badge"><?= count($entrada_detalle) ?></span></a></li>
            <li role="presentation"><a href="#imagenes" aria-controls="imagenes" role="tab" data-toggle="tab">Imagenes <span class="badge"><?= count($imagenes) ?></span></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="talla_color">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Id</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Talla</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Color</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>User_name</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha registro</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'>Cerrado</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Existencias</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad </th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($talla_color as $val):?>
                                        <tr style='font-size:90%;'>
                                            <td><?= $val->id_detalle?></td>
                                            <td style="text-align: right"><?= $val->talla->nombre_talla?></td>
                                            <td><?= $val->color->colores?></td>
                                            <td><?= $val->user_name?></td>
                                            <td><?= $val->fecha_registro?></td>
                                            <td><?= $val->cerradoDetalle?></td>
                                            <td style="text-align: right; background-color: #F5EEF8"><?= $val->stock_punto?></td>
                                            <td style="padding-right: 1; padding-right: 0; text-align: right"><input type="text" name="cantidad[]" style="text-align: right" value="<?= $val->cantidad ?>" size="5" > </td> 
                                            <input type="hidden" name="entrada_cantidad[]" value="<?= $val->id_detalle ?>">
                                            <td style= 'width: 25px; height: 25px;'>
                                                <?php if($val->cerrado == 0){?>
                                                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ', ['eliminar', 'id' => $model->id_inventario, 'id_detalle' => $val->id_detalle, 'token' => $token, 'codigo' => $codigo], [
                                                               'class' => '',
                                                               'data' => [
                                                                   'confirm' => 'Esta seguro de eliminar el registro?',
                                                                   'method' => 'post',
                                                               ],
                                                           ])
                                                    ?>
                                                <?php }?>
                                            </td>    
                                        </tr>
                                    <?php endforeach;?>
                                  
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            <!-- TERMINA TABAS-->
             <div role="tabpanel" class="tab-pane" id="listado_entradas">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr style='font-size:90%;'>
                                        <th scope="col" style='background-color:#B9D5CE;'>Nro entrada</th>                      
                                        <th scope="col" style='background-color:#B9D5CE;'>Fecha hora entrada</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'>Orden de compra</th>
                                        <th scope="col" style='background-color:#B9D5CE;'>Cantidad</th> 
                                         <th scope="col" style='background-color:#B9D5CE;'>User name</th> 
                                        <th scope="col" style='background-color:#B9D5CE;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($entrada_detalle as $val):?>
                                        <tr style='font-size:90%;'>
                                            <td><?= $val->entrada->id_entrada?></td>
                                            <td><?= $val->entrada->fecha_registro?></td>
                                            <?php if($val->entrada->id_orden_compra == null){?>
                                                 <td><?= 'NO HAY ORDEN DE COMPRA'?></td>
                                            <?php  }else{?>
                                                 <td><?= $val->entrada->ordenCompra->id_orden_compra?></td>
                                            <?php }?>     
                                            <td style = "text-align: right"><?= ''.number_format($val->cantidad,0)?></td>
                                            <td><?= $val->entrada->user_name_crear?></td>
                                        </tr>
                                    <?php endforeach;?>
                                  
                                </tbody>      
                            </table>
                        </div>
                    </div>   
                </div>
            </div>
            <!--TERMINA TABS-->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  
            <div role="tabpanel" class="tab-pane" id="imagenes">
                <div class="table-responsive">
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <div class="jumbotron">
                                <div class="container">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div id="carousel-example-captions" class="carousel slide" data-ride="carousel"> 
                                            <ol class="carousel-indicators">
                                                <?php for ($i=0; $i<count($imagenes); $i++):
                                                    $active = "active";?>
					            <li data-target="#carousel-example-captions" data-slide-to="<?php echo $i;?>" class="<?php echo $active;?>"></li>
					            <?php
						    $active = "";
                                                endfor;?>
                                            </ol>
                                            <div class="carousel-inner" role="listbox"> 
                                                <?php
                                                $active="active";
                                                foreach ($imagenes as $dato){
                                                   $cadena = 'Documentos/' . $dato->numero . '/' . $dato->codigo . '/' . $dato->nombre;
                                                   if($dato->extension == 'png' || $dato->extension == 'jpeg' || $dato->extension == 'jpg'){  ?>
                                                    <div class="item <?php echo $active;?>"> 
                                                        <img style="width: 100%; height: 100%" src="<?= $cadena;?>" data-holder-rendered = "true"> 
                                                            <div class="carousel-caption"> 
                                                               <p><?= $dato->descripcion;?></p>
                                                            </div> 
                                                    </div>
                                                    <?php
                                                    $active="";
                                                   } 
                                                } ?>
                                            </div> 
                                            <a class="left carousel-control" href="#carousel-example-captions" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> 
                                            <a class="right carousel-control" href="#carousel-example-captions" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> 
                                        </div>
                                    </div>
                                </div>    
                            </div>
                        </div>        
                    </div>   
                </div>
            </div>
            <!--TERMINA TABS-->
           

        </div>
    </div>    
  <?php $form->end() ?> 
</div>
  

