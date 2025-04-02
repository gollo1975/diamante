<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concepto_salarios".
 *
 * @property int $codigo_salario
 * @property string $nombre_concepto
 * @property int $compone_salario
 * @property int $inicio_nomina
 * @property int $aplica_porcentaje
 * @property double $porcentaje
 * @property double $porcentaje_tiempo_extra
 * @property int $prestacional
 * @property int $ingreso_base_prestacional
 * @property int $ingreso_base_cotizacion
 * @property int $debito_credito
 * @property int $adicion
 * @property int $auxilio_transporte
 * @property int $concepto_incapacidad
 * @property int $concepto_pension
 * @property int $concepto_salud
 * @property int $concepto_vacacion
 * @property int $provisiona_vacacion
 * @property int $provisiona_indemnizacion
 * @property int $tipo_adicion
 * @property int $recargo_nocturno
 * @property int $hora_extra
 * @property int $concepto_comision
 * @property int $concepto_licencia
 * @property int $fsp
 * @property int $concepto_prima
 * @property int $concepto_cesantias
 * @property int $intereses
 * @property string $fecha_creacion
 */
class ConceptoSalarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concepto_salarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_salario', 'nombre_concepto'], 'required'],
            [['codigo_salario', 'compone_salario', 'inicio_nomina', 'aplica_porcentaje', 'prestacional', 'ingreso_base_prestacional', 'ingreso_base_cotizacion', 'debito_credito', 'adicion', 'auxilio_transporte',
                'concepto_incapacidad', 'concepto_pension', 'concepto_salud', 'concepto_vacacion', 'provisiona_vacacion', 'provisiona_indemnizacion', 'tipo_adicion', 'recargo_nocturno', 'hora_extra',
                'concepto_comision', 'concepto_licencia', 'fsp', 'concepto_prima', 'concepto_cesantias', 'intereses','devengado_deduccion','id_agrupado'], 'integer'],
            [['porcentaje', 'porcentaje_tiempo_extra'], 'number'],
            [['fecha_creacion'], 'safe'],
            [['nombre_concepto'], 'string', 'max' => 150],
            [['codigo_salario'], 'unique'],
            [['id_agrupado'], 'exist', 'skipOnError' => true, 'targetClass' => AgruparConceptoSalario::className(), 'targetAttribute' => ['id_agrupado' => 'id_agrupado']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo_salario' => 'Codigo',
            'nombre_concepto' => 'Concepto',
            'compone_salario' => 'Compone Salario',
            'inicio_nomina' => 'Iniciar',
            'aplica_porcentaje' => 'Aplica Porcentaje',
            'porcentaje' => '%',
            'porcentaje_tiempo_extra' => '% Tiempo Extra',
            'prestacional' => 'Prestacional',
            'ingreso_base_prestacional' => 'Ingreso Base Prestacional',
            'ingreso_base_cotizacion' => 'Ingreso Base Cotizacion',
            'debito_credito' => 'Debito Credito',
            'adicion' => 'Adicion',
            'auxilio_transporte' => 'Auxilio Transporte',
            'concepto_incapacidad' => 'Concepto Incapacidad',
            'concepto_pension' => 'Concepto Pension',
            'concepto_salud' => 'Concepto Salud',
            'concepto_vacacion' => 'Concepto Vacacion',
            'provisiona_vacacion' => 'Provisiona Vacacion',
            'provisiona_indemnizacion' => 'Provisiona Indemnizacion',
            'tipo_adicion' => 'Tipo proceso',
            'recargo_nocturno' => 'Recargo Nocturno',
            'hora_extra' => 'Hora Extra',
            'concepto_comision' => 'Concepto Comision',
            'concepto_licencia' => 'Concepto Licencia',
            'fsp' => 'Fsp',
            'concepto_prima' => 'Concepto Prima',
            'concepto_cesantias' => 'Concepto Cesantias',
            'intereses' => 'Intereses',
            'fecha_creacion' => 'Fecha Creacion',
            'devengado_deduccion' => 'Devengado / Deduccion',
            'id_agrupado' => 'Nombre de grupo',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracionPension()
    {
        return $this->hasMany(ConfiguracionPension::className(), ['codigo_salario' => 'codigo_salario']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConceptoSalario()
    {
        return $this->hasOne(AgruparConceptoSalario::className(), ['id_agrupado' => 'id_agrupado']);
    }
    
    public function getCompone()
    {
        if($this->compone_salario == 1){
            $compone = "SI";
        }else{
            $compone = "NO";
        }
        return $compone;
    }
     public function getAplicaPorcentaje()
    {
        if($this->aplica_porcentaje == 1){
            $aplica = "SI";
        }else{
            $aplica = "NO";
        }
        return $aplica;
    }
     public function getPrestacion()
    {
        if($this->prestacional == 1){
            $prestacion = "SI";
        }else{
            $prestacion = "NO";
        }
        return $prestacion;
    }
    public function getIbpPrestacion()
    {
        if($this->ingreso_base_prestacional == 1){
            $ibpprestacion = "SI";
        }else{
            $ibpprestacion = "NO";
        }
        return $ibpprestacion;
    }
    public function getDebitocredito()
    {
        if($this->debito_credito == 1){
            $debitocredito = "SUMA";
        }elseif ($this->debito_credito == 2){
            $debitocredito = "RESTA";
        }else{
            $debitocredito = "NEUTRO";
        }
        
        return $debitocredito;
    }
    public function getAdicion()
    {
        if($this->adicion == 1){
            $adicion = "SI";
        }else{
            $adicion = "NO";
        }
        return $adicion;
    }
    public function getAuxilioTransporte()
    {
        if($this->auxilio_transporte == 1){
            $auxiliotransporte = "SI";
        }else{
            $auxiliotransporte = "NO";
        }
        return $auxiliotransporte;
    }
    public function getConceptoIncapacidad()
    {
        if($this->concepto_incapacidad == 1){
            $conceptoincapacidad= "SI";
        }else{
            $conceptoincapacidad = "NO";
        }
        return $conceptoincapacidad;
    }
     public function getConceptoPension()
    {
        if($this->concepto_pension == 1){
            $conceptopension= "SI";
        }else{
            $conceptopension = "NO";
        }
        return $conceptopension;
    }
      public function getConceptoSalud()
    {
        if($this->concepto_salud == 1){
            $conceptosalud= "SI";
        }else{
            $conceptosalud = "NO";
        }
        return $conceptosalud;
    }
      public function getConceptoVacacion()
    {
        if($this->concepto_vacacion == 1){
            $conceptovacacion= "SI";
        }else{
            $conceptovacacion = "NO";
        }
        return $conceptovacacion;
    }
     public function getProvisionaVacacion()
    {
        if($this->provisiona_vacacion == 1){
            $provisionavacacion= "SI";
        }else{
            $provisionavacacion = "NO";
        }
        return $provisionavacacion;
    }
     public function getProvisionaIndemnizacion()
    {
        if($this->provisiona_indemnizacion == 1){
            $provisionaindemnizacion= "SI";
        }else{
            $provisionaindemnizacion = "NO";
        }
        return $provisionaindemnizacion;
    }
    public function getTipoAdicion()
    {
        if($this->tipo_adicion == 1){
            $tipoadicion = "BONIFICACION";
        }elseif ($this->tipo_adicion == 2){
            $tipoadicion = "DESCUENTO";
        }else{
            $tipoadicion = "NO APLICA";
        }
        
        return $tipoadicion;
    }
    
     public function getRecargoNocturno()
    {
        if($this->recargo_nocturno == 1){
            $recargonocturno= "SI";
        }else{
            $recargonocturno = "NO";
        }
        return $recargonocturno;
    }
    
      public function getHoraExtra()
    {
        if($this->hora_extra == 1){
            $horaextra= "SI";
        }else{
            $horaextra = "NO";
        }
        return $horaextra;
    }
    
    
      public function getInicionomina()
    {
        if($this->inicio_nomina == 1){
            $inicionomina= "SI";
        }else{
            $inicionomina = "NO";
        }
        return $inicionomina;
    }
    public function getComision()
    {
        if($this->concepto_comision == 1){
            $comision= "SI";
        }else{
            $comision = "NO";
        }
        return $comision;
    }
    public function getConceptolicencia()
    {
        if($this->concepto_licencia == 1){
            $conceptolicencia= "SI";
        }else{
            $conceptolicencia = "NO";
        }
        return $conceptolicencia;
    }
      public function getFondoSP()
    {
        if($this->fsp == 1){
            $fondosp= "SI";
        }else{
            $fondosp = "NO";
        }
        return $fondosp;
    }
    
    public function getDevengadoDeduccion()
    {
        if($this->devengado_deduccion == 1){
            $devengadodeduccion= "CREDITO";
        }else{
            $devengadodeduccion = "DEDITO";
        }
        return $devengadodeduccion;
    }
    
    public function getDatoPrestacional()
    {
        if($this->prestacional == 0){
            $datoprestacional= "NO";
        }else{
            $datoprestacional = "SI";
        }
        return $datoprestacional;
    }
    
    public function getIbcCotizacion()
    {
        if($this->ingreso_base_prestacional == 0){
            $ibccotizacion= "NO";
        }else{
            $ibccotizacion = "SI";
        }
        return $ibccotizacion;
    }
    
    public function getConceptoPrima()
    {
        if($this->concepto_prima == 0){
            $conceptoprima = "NO";
        }else{
            $conceptoprima = "SI";
        }
        return $conceptoprima;
    }
    
      public function getConceptoCesantias()
    {
        if($this->concepto_cesantias == 0){
            $conceptocesantias = "NO";
        }else{
            $conceptocesantias = "SI";
        }
        return $conceptocesantias;
    }
    
       public function getConceptoIntereses()
    {
        if($this->intereses == 0){
            $conceptointereses = "NO";
        }else{
            $conceptointereses = "SI";
        }
        return $conceptointereses;
    }
    
    
}
