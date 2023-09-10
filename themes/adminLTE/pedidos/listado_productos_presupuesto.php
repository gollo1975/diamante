<?php
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

$this->title = 'Inventario de productos  ('. $model->cliente. ')';
$this->params['breadcrumbs'][] = ['label' => 'Presupuesto producto', 'url' => ['adicionar_presupuesto', 'id'=> $id, 'token' => $token]];
$this->params['breadcrumbs'][] = $model->id_pedido;
?>
<p>
    <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['view','id' =>$id, 'token' => $token], ['class' => 'btn btn-primary btn-sm']) ?>
</p>
    <div class="panel-body">
        <script language="JavaScript">
            function mostrarfiltro() {
                divC = document.getElementById("filtro");
                if (divC.style.display == "none"){divC.style.display = "block";}else{divC.style.display = "none";}
            }
        </script>
        <?php $formulario = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute(["pedidos/adicionar_presupuesto", 'id' => $id, 'token' => $token]),
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
                    <a align="right" href="<?= Url::toRoute(["pedidos/adicionar_presupuesto", 'id' => $id, 'token' => $token]) ?>" class="btn btn-primary btn-sm"><span class='glyphicon glyphicon-refresh'></span> Actualizar</a>
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
                <li role="presentation" class="active"><a href="#presupuesto" aria-controls="presupuesto" role="tab" data-toggle="tab">Inventarios <span class="badge"><?= $pagination->totalCount ?></span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="presupuesto">
                    <div class="table-responsive">
                        <div class="panel panel-success">
                           <div class="panel-body">
                                 <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr style="font-size: 90%;">
                                             <th scope="col" style='background-color:#B9D5CE;'>Código</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Producto</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Stock</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Imagen</th>
                                            <th scope="col" style='background-color:#B9D5CE;'>Cantidad enviada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($variable as $val): ?>
                                            <tr style="font-size: 90%;">
                                                <td><?= $val->codigo_producto ?></td>
                                                <td><?= $val->nombre_producto ?></td>
                                                <td style="background-color:#CBAAE3; color: black"><?= $val->stock_unidades ?></td>
                                                <td><?= 1 ?></td>
                                                <td style="padding-right: 1;padding-right: 0; text-align: left"> <input type="text" name="cantidades[]" style="text-align: right" size="7" maxlength="true"> </td> 
                                                <input type="hidden" name="nuevo_producto_presupueso[]" value="<?= $val->id_inventario?>"> 
                                            </tr>     
                                        <?php endforeach; ?>
                                    </tbody>     
                                </table>
                            </div>
                            <div class="panel-footer text-right">
                               <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Enviar productos", ["class" => "btn btn-success btn-sm", 'name' => 'importar_producto_presupuesto']) ?>
                            </div>
                        </div>
                        <?= LinkPager::widget(['pagination' => $pagination]) ?>
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
