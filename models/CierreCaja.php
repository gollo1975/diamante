<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cierre_caja".
 *
 * @property int $id_cierre
 * @property int $id_punto
 * @property string $fecha_inicio
 * @property string $fecha_corte
 * @property int $total_remision
 * @property int $total_factura
 * @property int $total_efectivo_factura
 * @property int $total_efectivo_remision
 * @property int $total_transacion_factura
 * @property int $total_transacion_remision
 * @property string $user_name
 * @property string $fecha_hora_registro
 *
 * @property PuntoVenta $punto
 */
class CierreCaja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cierre_caja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_punto', 'total_remision', 'total_factura', 'total_efectivo_factura', 'total_efectivo_remision', 'total_transacion_factura',
                'total_transacion_remision','autorizado','proceso_cerrado'], 'integer'],
            [['fecha_inicio', 'fecha_corte', 'fecha_hora_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_punto'], 'exist', 'skipOnError' => true, 'targetClass' => PuntoVenta::className(), 'targetAttribute' => ['id_punto' => 'id_punto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cierre' => 'Id Cierre',
            'id_punto' => 'Id Punto',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_corte' => 'Fecha Corte',
            'total_remision' => 'Total Remision',
            'total_factura' => 'Total Factura',
            'total_efectivo_factura' => 'Total Efectivo Factura',
            'total_efectivo_remision' => 'Total Efectivo Remision',
            'total_transacion_factura' => 'Total Transacion Factura',
            'total_transacion_remision' => 'Total Transacion Remision',
            'user_name' => 'User Name',
            'autorizado' => 'Autorizado:',
            'fecha_hora_registro' => 'Fecha Hora Registro',
            'proceso_cerrado' => 'Proceso cerrado:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPunto()
    {
        return $this->hasOne(PuntoVenta::className(), ['id_punto' => 'id_punto']);
    }
    
    public function getProcesoCerrado() {
        if($this->proceso_cerrado == 0){
             $procesocerrado = 'NO';
        }else{
            $procesocerrado = 'SI';
        }
        return $procesocerrado;
    }
    
    public function getAutorizado() {
        if($this->autorizado == 0){
             $autorizado = 'NO';
        }else{
            $autorizado = 'SI';
        }
        return $autorizado;
    }
}
