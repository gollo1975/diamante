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

$this->title = 'Cargar archivos';
$this->params['breadcrumbs'][] = ['label' => 'Cargar archivo', 'url' => ['validador_imagen']];
$this->params['breadcrumbs'][] = $model->id_inventario;
$view_archivo = 'inventario-productos';
?>
<div class="inventario-productos-view_archivo">

    <!--<?= Html::encode($this->title) ?>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['validador_imagen'], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-folder-open"></span> Archivos', ['directorio-archivos/index_archivo','numero' => 8, 'codigo' => $model->id_inventario,'view_archivo' => $view_archivo, 'token' => $token,], ['class' => 'btn btn-default btn-sm']) ?>
    </p>
    <div class="panel panel-success">
        <div class="panel-heading">
            INVENTARIO PRODUCTO TERMINADO
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Id') ?></th>
                    <td><?= Html::encode($model->id_inventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'Codigo') ?></th>
                    <td><?= Html::encode($model->codigo_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'nombre_producto') ?></th>
                    <td><?= Html::encode($model->nombre_producto) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_iva') ?></th>
                    <td><?= Html::encode($model->aplicaIva) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'costo_unitario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->costo_unitario,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'id_grupo') ?></th>
                    <td><?= Html::encode($model->grupo->nombre_grupo) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_proceso') ?></th>
                    <td><?= Html::encode($model->fecha_proceso) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_vencimiento') ?></th>
                    <td><?= Html::encode($model->fecha_vencimiento) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'fecha_creacion') ?></th>
                    <td><?= Html::encode($model->fecha_creacion) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'valor_iva') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->valor_iva,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'user_name') ?></th>
                    <td><?= Html::encode($model->user_name) ?></td>
                      <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'codigo_ean') ?></th>
                    <td><?= Html::encode($model->codigo_ean) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'unidades_entradas') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->unidades_entradas,0)) ?></td>
                   <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'stock_unidades') ?></th>
                    <td style="text-align: right; background-color:#F5EEF8;"><?= Html::encode(''.number_format($model->stock_unidades,0)) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'subtotal') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->subtotal,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'inventario_inicial') ?></th>
                    <td><?= Html::encode($model->inventarioInicial) ?></td>
                     <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'aplica_inventario') ?></th>
                    <td><?= Html::encode($model->aplicaInventario) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'porcentaje_iva') ?></th>
                    <td colspan="3"><?= Html::encode($model->porcentaje_iva) ?></td>
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'total_inventario') ?></th>
                    <td style="text-align: right;"><?= Html::encode(''.number_format($model->total_inventario,0)) ?></td>
                </tr>
                <tr style="font-size: 90%;">
                    <th style='background-color:#F0F3EF;'><?= Html::activeLabel($model, 'descripcion_producto') ?></th>
                    <td colspan="9"><?= Html::encode($model->descripcion_producto)?></td>
                </tr>
                
                
            </table>
        </div>
    </div>
    <!--INICIO LOS TABS-->
</div>
