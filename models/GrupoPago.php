<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grupo_pago".
 *
 * @property int $id_grupo_pago
 * @property string $grupo_pago
 * @property string $codigo_departamento
 * @property string $codigo_municipio
 * @property int $id_sucursal
 * @property string $ultimo_pago_nomina
 * @property string $ultimo_pago_prima
 * @property string $ultimo_pago_cesantia
 * @property int $limite_devengado
 * @property int $dias_pago
 * @property int $estado
 * @property string $observacion
 * @property string $user_name
 * @property string $fecha_hora_registro
 */
class GrupoPago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grupo_pago';
    }
    
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->grupo_pago = strtoupper($this->grupo_pago); 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo_pago', 'codigo_departamento', 'codigo_municipio', 'id_sucursal', 'ultimo_pago_nomina', 'ultimo_pago_prima',
                'ultimo_pago_cesantia', 'dias_pago', 'id_periodo_pago'], 'required'],
            [['id_sucursal', 'limite_devengado', 'dias_pago', 'estado'], 'integer'],
            [['ultimo_pago_nomina', 'ultimo_pago_prima', 'ultimo_pago_cesantia', 'fecha_hora_registro'], 'safe'],
            [['grupo_pago'], 'string', 'max' => 40],
            [['codigo_departamento', 'codigo_municipio'], 'string', 'max' => 10],
            [['observacion'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
            [['codigo_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => Municipios::className(), 'targetAttribute' => ['codigo_municipio' => 'codigo_municipio']],
            [['codigo_departamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['codigo_departamento' => 'codigo_departamento']],
            [['id_sucursal'], 'exist', 'skipOnError' => true, 'targetClass' => SucursalSeguridadSocial::className(), 'targetAttribute' => ['id_sucursal' => 'id_sucursal']],
            [['id_periodo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoPago::className(), 'targetAttribute' => ['id_periodo_pago' => 'id_periodo_pago']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_grupo_pago' => 'Id',
            'grupo_pago' => 'Grupo pago',
            'codigo_departamento' => 'Departamento',
            'codigo_municipio' => 'Municipio',
            'id_sucursal' => 'Sucursal',
            'ultimo_pago_nomina' => 'Ultimo Pago Nomina',
            'ultimo_pago_prima' => 'Ultimo Pago Prima',
            'ultimo_pago_cesantia' => 'Ultimo Pago Cesantia',
            'limite_devengado' => 'Limite devengado',
            'dias_pago' => 'Dias Pago',
            'estado' => 'Activo',
            'observacion' => 'Observacion',
            'user_name' => 'User Name',
            'fecha_hora_registro' => 'Fecha Hora Registro',
            'id_periodo_pago' => 'Periodo de pago',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartamento()
    {
        return $this->hasOne(Departamentos::className(), ['codigo_departamento' => 'codigo_departamento']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getMunicipios()
    {
        return $this->hasOne(Municipios::className(), ['codigo_municipio' => 'codigo_municipio']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getSucursalPila()
    {
        return $this->hasOne(SucursalSeguridadSocial::className(), ['id_sucursal' => 'id_sucursal']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoPago()
    {
        return $this->hasOne(PeriodoPago::className(), ['id_periodo_pago' => 'id_periodo_pago']);
    }
    
    public function getActivo() {
        if($this->estado == 0){
            $estado = 'SI';
        }else{
            $estado = 'NO';
        }
        return $estado;
    }
    
}
