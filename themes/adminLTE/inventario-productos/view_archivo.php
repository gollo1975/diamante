<?php
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

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'IMAGENES';
$this->params['breadcrumbs'][] = ['label' => 'Ver imagenes', 'url' => ['view_archivo']];
$this->params['breadcrumbs'][] = $model->id_inventario;

?>
<div class="inventario-productos-view_archivo">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view_archivo'], ['class' => 'btn btn-primary btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            INVENTARIO PRODUCTO TERMINADO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 85%;">
                   
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo') ?></th>
                    <td><?= Html::encode($model->grupo->nombre_grupo) ?></td>
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
    <!--INICIO LOS TABS-->
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#listado_imagenes" aria-controls="listado_imagenes" role="tab" data-toggle="tab">Imagenes <span class="badge"><?= count($imagenes) ?></span></a></li>
        </ul>
        <div class="tab-content">
             <div role="tabpanel" class="tab-pane active" id="listado_imagenes">
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
        </div>
    </div>     
    <?php $form->end() ?> 
</div>
