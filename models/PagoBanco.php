<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pago_banco".
 *
 * @property int $id_pago_banco
 * @property int $id_empresa
 * @property int $nit_cedula
 * @property string $codigo_banco
 * @property int $tipo_pago
 * @property int $id_tipo_nomina
 * @property string $aplicacion
 * @property string $secuencia
 * @property string $fecha_creacion
 * @property string $fecha_aplicacion
 * @property int $total_empleados
 * @property int $total_pagar
 * @property string $adicion_numero
 * @property int $debitos
 * @property string $descripcion
 * @property string $user_name
 * @property int $autorizado
 * @property int $cerrar_proceso
 * @property string $fecha_hora_registro
 *
 * @property MatriculaEmpresa $empresa
 * @property EntidadBancarias $codigoBanco
 * @property TipoNomina $tipoNomina
 */
class PagoBanco extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pago_banco';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'nit_cedula', 'tipo_pago', 'id_tipo_nomina', 'total_empleados', 'total_pagar', 'debitos', 'autorizado', 'cerrar_proceso'], 'integer'],
            [['codigo_banco', 'tipo_pago', 'id_tipo_nomina', 'aplicacion', 'secuencia', 'fecha_creacion', 'fecha_aplicacion'], 'required'],
            [['fecha_creacion', 'fecha_aplicacion', 'fecha_hora_registro'], 'safe'],
            [['codigo_banco', 'descripcion'], 'string', 'max' => 10],
            [['aplicacion'], 'string', 'max' => 1],
            [['secuencia', 'adicion_numero'], 'string', 'max' => 2],
            [['user_name'], 'string', 'max' => 15],
            [['id_empresa'], 'exist', 'skipOnError' => true, 'targetClass' => MatriculaEmpresa::className(), 'targetAttribute' => ['id_empresa' => 'id_empresa']],
            [['codigo_banco'], 'exist', 'skipOnError' => true, 'targetClass' => EntidadBancarias::className(), 'targetAttribute' => ['codigo_banco' => 'codigo_banco']],
            [['id_tipo_nomina'], 'exist', 'skipOnError' => true, 'targetClass' => TipoNomina::className(), 'targetAttribute' => ['id_tipo_nomina' => 'id_tipo_nomina']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pago_banco' => 'Id Pago Banco',
            'id_empresa' => 'Id Empresa',
            'nit_cedula' => 'Nit Cedula',
            'codigo_banco' => 'Entidad bancaria:',
            'tipo_pago' => 'Tipo proceso:',
            'id_tipo_nomina' => 'Tipo Pago:',
            'aplicacion' => 'Aplicacion:',
            'secuencia' => 'Secuencia:',
            'fecha_creacion' => 'Fecha creacion:',
            'fecha_aplicacion' => 'Fecha aplicacion:',
            'total_empleados' => 'Total Empleados',
            'total_pagar' => 'Total Pagar',
            'adicion_numero' => 'Adicion Numero',
            'debitos' => 'Debitos',
            'descripcion' => 'Descripcion',
            'user_name' => 'User Name',
            'autorizado' => 'Autorizado',
            'cerrar_proceso' => 'Cerrar Proceso',
            'fecha_hora_registro' => 'Fecha Hora Registro',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(MatriculaEmpresa::className(), ['id_empresa' => 'id_empresa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoBanco()
    {
        return $this->hasOne(EntidadBancarias::className(), ['codigo_banco' => 'codigo_banco']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoNomina()
    {
        return $this->hasOne(TipoNomina::className(), ['id_tipo_nomina' => 'id_tipo_nomina']);
    }
    
    public function getEstadoAutorizado() {
     if($this->autorizado == 0){
         $estadoautorizado = 'NO';
     }else{
         $estadoautorizado = 'SI';
     }
     return $estadoautorizado;
    }
    
     public function getEstadoCerrado() {
     if($this->cerrar_proceso == 0){
         $estadocerrado = 'NO';
     }else{
         $estadocerrado = 'SI';
     }
     return $estadocerrado;
    }
    
    //PROCESO QUE BUSCA EL TIPO PROCESO
    
     public function getTipoProceso()
    {
        if($this->tipo_proceso == 1){
            $tipoproceso = 'PAGO VINCULADOS';
        }else{
            $tipoproceso = 'PAGO PRESTACION DE SERVICIOS';
        }
        return $tipoproceso;
    }
    
      public function getAplicacionPago()
    {
        if($this->aplicacion == 'I'){
            $aplicacionpago = 'INMEDIATO';
        }else{
            if($this->aplicacion == 'M'){
              $aplicacionpago = 'MEDIO DIA';
            }else{
                  $aplicacionpago = 'NOCHE';
            }
        }
        return $aplicacionpago;
    }
    
    // proceso tipo de pago
     public function getTipoPago()
    {
        if($this->tipo_pago == 220){
            $tipopago = 'PAGO PROVEEDORES';
        }else{
            $tipopago = 'PAGO NOMINA';
        }
        return $tipopago;
    }
}
