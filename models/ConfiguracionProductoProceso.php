<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_producto_proceso".
 *
 * @property int $id_proceso
 * @property int $id_analisis
 * @property int $id_grupo
 * @property int $id_especificacion
 * @property string $resultado
 * @property string $user_name
 * @property string $fecha_registro
 *
 * @property ConceptoAnalisis $analisis
 * @property GrupoProducto $grupo
 * @property EspecificacionProducto $especificacion
 */
class ConfiguracionProductoProceso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion_producto_proceso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_analisis', 'resultado'], 'required'],
            [['id_analisis', 'id_grupo', 'id_especificacion','id_etapa'], 'integer'],
            [['fecha_registro'], 'safe'],
            [['resultado'], 'string', 'max' => 20],
            [['user_name'], 'string', 'max' => 15],
            [['id_analisis'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoAnalisis::className(), 'targetAttribute' => ['id_analisis' => 'id_analisis']],
            [['id_grupo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoProducto::className(), 'targetAttribute' => ['id_grupo' => 'id_grupo']],
            [['id_especificacion'], 'exist', 'skipOnError' => true, 'targetClass' => EspecificacionProducto::className(), 'targetAttribute' => ['id_especificacion' => 'id_especificacion']],
            [['id_etapa'], 'exist', 'skipOnError' => true, 'targetClass' => EtapasAuditoria::className(), 'targetAttribute' => ['id_etapa' => 'id_etapa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_proceso' => 'Id Proceso',
            'id_analisis' => 'Id Analisis',
            'id_grupo' => 'Id Grupo',
            'id_especificacion' => 'Id Especificacion',
            'resultado' => 'Resultado',
            'user_name' => 'User Name',
            'fecha_registro' => 'Fecha Registro',
            'id_etapa' => 'Etapaa:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalisis()
    {
        return $this->hasOne(ConceptoAnalisis::className(), ['id_analisis' => 'id_analisis']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(GrupoProducto::className(), ['id_grupo' => 'id_grupo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEspecificacion()
    {
        return $this->hasOne(EspecificacionProducto::className(), ['id_especificacion' => 'id_especificacion']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtapas()
    {
        return $this->hasOne(EtapasAuditoria::className(), ['id_etapa' => 'id_etapa']);
    }
}
