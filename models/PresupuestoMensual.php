<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presupuesto_mensual".
 *
 * @property int $id_mensual
 * @property int $id_presupuesto
 * @property string $fecha_inicio
 * @property string $fecha_corte
 * @property int $valor_gastado
 * @property string $fecha_cracion
 * @property string $user_name
 * @property int $total_registro
 * @property string $observacion
 *
 * @property PresupuestoEmpresarial $presupuesto
 */
class PresupuestoMensual extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presupuesto_mensual';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_presupuesto', 'fecha_inicio', 'fecha_corte'], 'required'],
            [['id_presupuesto', 'valor_gastado', 'total_registro','autorizado','cerrado'], 'integer'],
            [['fecha_inicio', 'fecha_corte', 'fecha_creacion'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 100],
            [['id_presupuesto'], 'exist', 'skipOnError' => true, 'targetClass' => PresupuestoEmpresarial::className(), 'targetAttribute' => ['id_presupuesto' => 'id_presupuesto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_mensual' => 'Id',
            'id_presupuesto' => 'Presupuesto:',
            'fecha_inicio' => 'Fecha Inicio:',
            'fecha_corte' => 'Fecha Corte:',
            'valor_gastado' => 'Valor gastado:',
            'fecha_creacion' => 'Fecha Cracion:',
            'user_name' => 'User Name:',
            'total_registro' => 'Total registro:',
            'observacion' => 'Observacion:',
            'autorizado' => 'Autorizado:',
            'cerrado' => 'Cerrado:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresupuesto()
    {
        return $this->hasOne(PresupuestoEmpresarial::className(), ['id_presupuesto' => 'id_presupuesto']);
    }
    
    public function getAutorizadoMes() {
        if($this->autorizado == 0){
            $autorizadomes = 'NO';
        }else{
            $autorizadomes = 'SI';
        }
        return $autorizadomes;
    }
    
     public function getCerradoMes() {
        if($this->cerrado == 0){
            $cerradomes = 'NO';
        }else{
            $cerradomes = 'SI';
        }
        return $cerradomes;
    }
    
}
