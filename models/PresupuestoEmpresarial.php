<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presupuesto_empresarial".
 *
 * @property int $id_presupuesto
 * @property string $descripcion
 * @property int $valor_presupuesto
 * @property int $id_area
 * @property int $año
 * @property string $fecha_inicio
 * @property string $fecha_corte
 * @property string $user_name
 * @property int $estado
 * @property string $fecha_registro
 *
 * @property AreaEmpresa $area
 */
class PresupuestoEmpresarial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presupuesto_empresarial';
    }
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->descripcion = strtoupper($this->descripcion); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'valor_presupuesto', 'id_area', 'año', 'fecha_inicio', 'fecha_corte'], 'required'],
            [['valor_presupuesto', 'id_area', 'año', 'estado','valor_gastado','anio_cerrado'], 'integer'],
            [['fecha_inicio', 'fecha_corte', 'fecha_registro'], 'safe'],
            [['descripcion'], 'string', 'max' => 20],
            [['user_name'], 'string', 'max' => 15],
            [['id_area'], 'exist', 'skipOnError' => true, 'targetClass' => AreaEmpresa::className(), 'targetAttribute' => ['id_area' => 'id_area']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_presupuesto' => 'Id',
            'descripcion' => 'Descripcion',
            'valor_presupuesto' => 'Presupuesto',
            'id_area' => 'Area',
            'año' => 'Año',
            'fecha_inicio' => 'Fecha inicio',
            'fecha_corte' => 'Fecha corte',
            'user_name' => 'User name',
            'estado' => 'Activo',
            'fecha_registro' => 'Fecha Registro',
            'valor_gastado' => 'Vr. gastado',
            'anio_cerrado' => 'Año cerrado:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(AreaEmpresa::className(), ['id_area' => 'id_area']);
    }
    public function getEstadoRegistro() {
        if($this->estado == 0){
            $estadoregistro = 'SI';
        }else{
            $estadoregistro = 'NO';
        }
        return $estadoregistro;
    }
}
