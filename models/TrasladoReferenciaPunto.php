<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "traslado_referencia_punto".
 *
 * @property int $id_traslado
 * @property int $id_inventario
 * @property int $id_punto_saliente
 * @property int $id_punto_entrante
 * @property int $unidades
 * @property string $fecha_proceso
 * @property string $fecha_hora_regisro
 * @property string $user_name
 *
 * @property InventarioPuntoVenta $inventario
 * @property PuntoVenta $puntoSaliente
 * @property PuntoVenta $puntoEntrante
 */
class TrasladoReferenciaPunto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'traslado_referencia_punto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_inventario_saliente', 'id_punto_saliente', 'id_punto_entrante', 'unidades','id_talla','id_color','aplicado','id_inventario_entrante'], 'integer'],
            [['fecha_proceso', 'fecha_hora_registro'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_inventario_saliente'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioPuntoVenta::className(), 'targetAttribute' => ['id_inventario_saliente' => 'id_inventario']],
            [['id_punto_saliente'], 'exist', 'skipOnError' => true, 'targetClass' => PuntoVenta::className(), 'targetAttribute' => ['id_punto_saliente' => 'id_punto']],
            [['id_punto_entrante'], 'exist', 'skipOnError' => true, 'targetClass' => PuntoVenta::className(), 'targetAttribute' => ['id_punto_entrante' => 'id_punto']],
            [['id_talla'], 'exist', 'skipOnError' => true, 'targetClass' => Tallas::className(), 'targetAttribute' => ['id_talla' => 'id_talla']],
            [['id_color'], 'exist', 'skipOnError' => true, 'targetClass' => Colores::className(), 'targetAttribute' => ['id_color' => 'id_color']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_traslado' => 'Id Traslado',
            'id_inventario_saliente' => 'Saliente',
            'id_inventario_entrante' => 'Entrante',
            'id_punto_saliente' => 'Id Punto Saliente',
            'id_punto_entrante' => 'Id Punto Entrante',
            'unidades' => 'Unidades',
            'fecha_proceso' => 'Fecha Proceso',
            'fecha_hora_registro' => 'Fecha Hora Regisro',
            'user_name' => 'User Name',
            'id_talla' => 'Tallas:',
            'id_color' => 'Colores:',
            'aplicado' => 'Aplicado:',
           
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventario()
    {
        return $this->hasOne(InventarioPuntoVenta::className(), ['id_inventario' => 'id_inventario_saliente']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getColores()
    {
        return $this->hasOne(Colores::className(), ['id_color' => 'id_color']);
    }
      /**
     * @return \yii\db\ActiveQuery
     */
    public function getTallas()
    {
        return $this->hasOne(Tallas::className(), ['id_talla' => 'id_talla']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPuntoSaliente()
    {
        return $this->hasOne(PuntoVenta::className(), ['id_punto' => 'id_punto_saliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPuntoEntrante()
    {
        return $this->hasOne(PuntoVenta::className(), ['id_punto' => 'id_punto_entrante']);
    }
    
    public function getRegistroAplicado() {
        if($this->aplicado == 0){
            $registroaplicado = 'NO';
        }else{
            $registroaplicado = 'SI';
        }
        return $registroaplicado;
    }
}
