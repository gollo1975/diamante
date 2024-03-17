<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proveedor_estudio_detalles".
 *
 * @property int $id
 * @property int $id_estudio
 * @property int $id_requisito
 * @property string $requisito
 * @property double $porcentaje
 * @property int $aplica
 * @property int $documento_fisico
 * @property string $observacion
 * @property string $fecha_registro
 *
 * @property ProveedorEstudios $estudio
 * @property ListadoRequisitos $requisito0
 */
class ProveedorEstudioDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proveedor_estudio_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_estudio', 'id_requisito', 'aplica', 'documento_fisico','validado','cumplio'], 'integer'],
            [['porcentaje'], 'number'],
            [['fecha_registro'], 'safe'],
            [['requisito', 'observacion'], 'string', 'max' => 40],
            [['id_estudio'], 'exist', 'skipOnError' => true, 'targetClass' => ProveedorEstudios::className(), 'targetAttribute' => ['id_estudio' => 'id_estudio']],
            [['id_requisito'], 'exist', 'skipOnError' => true, 'targetClass' => ListadoRequisitos::className(), 'targetAttribute' => ['id_requisito' => 'id_requisito']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_estudio' => 'Id Estudio',
            'id_requisito' => 'Id Requisito',
            'requisito' => 'Requisito',
            'porcentaje' => 'Porcentaje',
            'aplica' => 'Aplica',
            'documento_fisico' => 'Documento Fisico',
            'observacion' => 'Observacion',
            'fecha_registro' => 'Fecha Registro',
            'validado' => 'validado',
            'cumplio' => 'Cumple:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudio()
    {
        return $this->hasOne(ProveedorEstudios::className(), ['id_estudio' => 'id_estudio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequisito()
    {
        return $this->hasOne(ListadoRequisitos::className(), ['id_requisito' => 'id_requisito']);
    }
    
     public function getValidadoEstudio() {
        if($this->validado == 0 ){
            $validadoestudio = 'NO';
        }else{
            $validadoestudio = 'SI';
        }
        return $validadoestudio;
    }
     public function getCumpleRequisito() {
        if($this->cumplio == 0 ){
            $cumplerequisito = 'NO';
        }else{
            $cumplerequisito = 'SI';
        }
        return $cumplerequisito;
    }
    
    public function getAplicaEstudio() {
        if($this->aplica == 0 ){
            $aplicaestudio = 'NO';
        }else{
            $aplicaestudio = 'SI';
        }
        return $aplicaestudio;
    }
    
    public function getDocumentoFisico() {
        if($this->documento_fisico == 0 ){
            $documentofisico = 'NO';
        }else{
            $documentofisico = 'SI';
        }
        return $documentofisico;
    }
}
