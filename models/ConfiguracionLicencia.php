<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_licencia".
 *
 * @property int $codigo_licencia
 * @property string $concepto
 * @property int $afecta_salud
 * @property int $ausentismo
 * @property int $maternidad
 * @property int $paternidad
 * @property int $suspension_contrato
 * @property int $remunerada
 * @property int $codigo_salario
 * @property double $porcentaje
 * @property int $codigo
 * @property string $user_name
 *
 * @property ConceptoSalarios $codigoSalario
 */
class ConfiguracionLicencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_licencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto','codigo_salario'], 'required'],
            [['afecta_salud', 'ausentismo', 'maternidad', 'paternidad', 'suspension_contrato', 'remunerada', 'codigo_salario', 'codigo'], 'integer'],
            [['porcentaje'], 'number'],
            [['concepto'], 'string', 'max' => 120],
            [['user_name'], 'string', 'max' => 15],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo_licencia' => 'Id',
            'concepto' => 'Concepto',
            'afecta_salud' => 'Afecta salud',
            'ausentismo' => 'Ausentismo',
            'maternidad' => 'Maternidad',
            'paternidad' => 'Paternidad',
            'suspension_contrato' => 'Suspension',
            'remunerada' => 'Remunerada',
            'codigo_salario' => 'Codigo de salario',
            'porcentaje' => 'Porcentaje',
            'codigo' => 'Codigo',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoSalario()
    {
        return $this->hasOne(ConceptoSalarios::className(), ['codigo_salario' => 'codigo_salario']);
    }
    
    public function getAfectaSalud() {
        if($this->afecta_salud == 0){
            $afectasalud = 'NO';
        }else{
            $afectasalud = 'SI';
        }
        return $afectasalud;
    }
    
    public function getlAusentismo()
    {
        if($this->ausentismo == 1){
            $lausentismo= "SI";
        }else{
            $lausentismo = "NO";
        }
        return $lausentismo;
    }
     public function getlMaternidad()
    {
        if($this->maternidad == 1){
            $lmaternidad= "SI";
        }else{
            $lmaternidad = "NO";
        }
        return $lmaternidad;
    }
     public function getlicenciaPaternidad()
    {
        if($this->paternidad == 1){
            $licenciapaternidad= "SI";
        }else{
            $licenciapaternidad = "NO";
        }
        return $licenciapaternidad;
    }
     public function getlRemunerada()
    {
        if($this->remunerada == 1){
            $lremunerada= "SI";
        }else{
            $lremunerada = "NO";
        }
        return $lremunerada;
    }
     public function getsuspensionContrato()
    {
        if($this->suspension_contrato == 1){
            $suspensioncontrato= "SI";
        }else{
            $suspensioncontrato = "NO";
        }
        return $suspensioncontrato;
    }
}
