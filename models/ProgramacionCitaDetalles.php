<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programacion_cita_detalles".
 *
 * @property int $id_visita
 * @property int $id_programacion
 * @property int $id_cliente
 * @property int $id_tipo_visita
 * @property string $hora_visita
 * @property string $nota
 * @property string $fecha_registro
 *
 * @property ProgramacionCitas $programacion
 * @property Clientes $cliente
 * @property TipoVisitaComercial $tipoVisita
 */
class ProgramacionCitaDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programacion_cita_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_programacion', 'id_cliente', 'id_tipo_visita','cumplida','tipo_visita'], 'integer'],
            [['id_cliente', 'id_tipo_visita', 'hora_visita'], 'required'],
            [['hora_visita'], 'time'],
            [['fecha_registro','fecha_informe','fecha_cita_comercial'], 'safe'],
            [['nota'], 'string', 'max' => 100],
            [['descripcion_gestion'], 'string'],
            [['desde', 'hasta'], 'safe'],
            [['id_programacion'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramacionCitas::className(), 'targetAttribute' => ['id_programacion' => 'id_programacion']],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_tipo_visita'], 'exist', 'skipOnError' => true, 'targetClass' => TipoVisitaComercial::className(), 'targetAttribute' => ['id_tipo_visita' => 'id_tipo_visita']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_visita' => 'Id Visita',
            'id_programacion' => 'Id Programacion',
            'id_cliente' => 'Id Cliente',
            'id_tipo_visita' => 'Id Tipo Visita',
            'hora_visita' => 'Hora Visita',
            'nota' => 'Nota',
            'fecha_registro' => 'Fecha Registro',
            'desde' => 'Desde',
            'hasta' => 'Hasta;',
            'cumplida' => 'Cumplida:',
            'fecha_informe' => 'Fecha informe:',
            'descripcion_gestion' => 'Gestion visita:',
            'tipo_visita' => 'Tipo visita:',
            'fecha_cita_comercial' => 'fecha_cita_comercial',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramacion()
    {
        return $this->hasOne(ProgramacionCitas::className(), ['id_programacion' => 'id_programacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoVisita()
    {
        return $this->hasOne(TipoVisitaComercial::className(), ['id_tipo_visita' => 'id_tipo_visita']);
    }
    public function getCitaCumplida() {
        if($this->cumplida == 0){
            $citacumplida = 'NO';
        }else{
            $citacumplida = 'SI';
        }
        return $citacumplida;
    }
    
    public function getVisitaCliente() {
        if($this->tipo_visita == 1){
            $visitacliente = 'TELEFONICA';
        }else{
            if($this->tipo_visita == 2){
               $visitacliente = 'VIRTUAL';
            }else{
                $visitacliente = 'PRESENCIAL';
            }
        }
        return $visitacliente;
    }
}
