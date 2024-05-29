<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
//Modelos...
$this->title = 'PARAMETROS DEL PRESUPUESTO';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-success">
    <div class="panel-body">
        <script language="JavaScript">
            function mostrarfiltro() {
                divC = document.getElementById("filtro");
                if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
            }
        </script>
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["inventario-productos/asignar_producto_presupuesto"]),
            "enableClientValidation" => true,
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-3 form-group">{input}{error}</div>',
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            'options' => []
                        ],
        ]);
        ?>
        <div class="panel panel-success panel-filters">
            <div class="panel-heading" onclick="mostrarfiltro()">
                Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
            </div>
            <div class="panel-body" id="filtro" style="display:none">
                <div class="row" >
                    <?= $formulario->field($form, "q")->input("search") ?>
                    <?= $formulario->field($form, "nombre")->input("search") ?>
                </div>

                <div class="panel-footer text-right">
                    <?= Html::submitButton("<span class='glyphicon glyphicon-search'></span> Buscar", ["class" => "btn btn-primary btn-sm",]) ?>
                    <a align="right" href="<?= Url::toRoute(["inventario-productos/asignar_producto_presupuesto"]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
                </div>
            </div>
        </div>
        <?php $formulario->end() ?>
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]); ?>
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#inventario" aria-controls="inventario" role="tab" data-toggle="tab">Inventarios <span class="badge"><?= $pagination->totalCount ?></span></a></li>
                <li role="presentation"><a href="#listadoparametro" aria-controls="listadoparametro" role="tab" data-toggle="tab">Presupuesto <span class="badge"><?= count($parametros) ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="inventario">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                           <div class="panel-body">
                                 <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Nombre_producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Imagen</th>
                                            <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $variable = '';
                                        $item = \app\models\Documentodir::findOne(8);
                                        foreach ($model as $val): 
                                             $valor = app\models\DirectorioArchivos::find()->where(['=','codigo', $val->id_inventario])->andWhere(['=','numero', $item->codigodocumento])->one();
                                            ?>
                                            <tr style ='font-size: 90%;'>                
                                                <td><?= $val->codigo_producto?></td>
                                                <td><?= $val->nombre_producto?></td>
                                                <?php if($valor){
                                                    $variable = 'Documentos/'.$valor->numero.'/'.$valor->codigo.'/'. $valor->nombre;
                                                    if($valor->extension == 'png' || $valor->extension == 'jpeg' || $valor->extension == 'jpg'){?>
                                                        <td style="width: 100px; background-color: white" title="<?php echo $val->nombre_producto?>"> <?= yii\bootstrap\Html::img($variable, ['width' => '70px;', 'height' => '75px;'])?></td>
                                                    <?php }else {?>
                                                      <td><?= 'NOT FOUND'?></td>
                                                    <?php } 
                                                }else{?>
                                                      <td></td>
                                                <?php }?>      
                                                <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="nuevo_producto_presupuesto[]" value="<?= $val->id_inventario ?>"></td> 
                                            </tr>            
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="panel-footer text-right">
                                <?= Html::submitButton("<span class='glyphicon glyphicon-plus'></span> Asignar", ["class" => "btn btn-info btn-sm", 'name' => 'cargar_producto']) ?>
                            </div>
                          
                        </div>
                        <?= LinkPager::widget(['pagination' => $pagination]) ?>
                    </div>
                </div>    
                <!-- TERMINA TABS-->  
                <div role="tabpanel" class="tab-pane" id="listadoparametro">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                          <div class="panel-body">
                                 <table class="table table-bordered table-striped table-hover">
                                   <thead>
                                       <tr style="font-size: 90%;">
                                            <th scope="col" style='background-color:#B9D5CE;'>Codigo</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Nombre_producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Imagen</th>
                                            <th scope="col" style='background-color:#B9D5CE;'><input type="checkbox" onclick="marcar(this);"/></th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                        <?php
                                        $subtotal = 0; $impuesto = 0; $total = 0;
                                        $variable = '';
                                        $item = \app\models\Documentodir::findOne(8);
                                        foreach ($parametros as $val):
                                            $valor = app\models\DirectorioArchivos::find()->where(['=','codigo', $val->id_inventario])->andWhere(['=','numero', $item->codigodocumento])->one();
                                            ?>
                                             <tr style ='font-size: 90%;'>                
                                                <td><?= $val->codigo_producto?></td>
                                                <td><?= $val->nombre_producto?></td>
                                                <?php if($valor){
                                                    $variable = 'Documentos/'.$valor->numero.'/'.$valor->codigo.'/'. $valor->nombre;
                                                    if($valor->extension == 'png' || $valor->extension == 'jpeg' || $valor->extension == 'jpg'){?>
                                                        <td style="width: 100px; background-color: white" title="<?php echo $val->nombre_producto?>"> <?= yii\bootstrap\Html::img($variable, ['width' => '70px;', 'height' => '75px;'])?></td>
                                                    <?php }else {?>
                                                      <td><?= 'NOT FOUND'?></td>
                                                    <?php } 
                                                }else{?>
                                                      <td></td>
                                                <?php }?>   
                                                <td style= 'width: 25px; height: 25px;'><input type="checkbox" name="quitar_producto[]" value="<?= $val->id_inventario ?>"></td> 
                                            </tr>     
                                        <?php endforeach; ?>
                                    </tbody>    
                               </table>
                           </div>
                             <div class="panel-footer text-right">
                                <?= Html::submitButton("<span class='glyphicon glyphicon-circle-arrow-left'></span> Revertir", ["class" => "btn btn-success btn-sm", 'name' => 'liberar_producto']) ?>
                            </div>
                       </div>
                </div>
            </div>        
             <!-- TERMINA TABS-->
        </div>     
    </div>        
    <?php ActiveForm::end(); ?>    
</div> 

<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
</script>
