<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prorroga_contrato".
 *
 * @property int $id_prorroga_contrato
 * @property int $id_contrato
 * @property int $id_formato_contenido
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property string $fecha_creacion
 * @property string $fecha_ultima_contrato
 * @property string $fecha_nueva_renovacion
 * @property string $fecha_preaviso
 * @property int $dias_preaviso
 * @property int $dias_contratados
 * @property string $user_name
 *
 * @property Contratos $contrato
 * @property FormatoContenido $formatoContenido
 */
class ProrrogaContrato extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prorroga_contrato';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_contrato', 'id_formato_contenido', 'dias_preaviso', 'dias_contratados'], 'integer'],
            [['fecha_desde', 'fecha_hasta', 'fecha_creacion', 'fecha_ultima_contrato', 'fecha_nueva_renovacion', 'fecha_preaviso'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_formato_contenido'], 'required'],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_formato_contenido'], 'exist', 'skipOnError' => true, 'targetClass' => FormatoContenido::className(), 'targetAttribute' => ['id_formato_contenido' => 'id_formato_contenido']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_prorroga_contrato' => 'Codigo',
            'id_contrato' => 'No de contrato',
            'id_formato_contenido' => 'Formato de impresion',
            'fecha_desde' => 'Fecha inicio contrato:',
            'fecha_hasta' => 'Hasta:',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_ultima_contrato' => 'Fecha de finalizacion:',
            'fecha_nueva_renovacion' => 'Nueva fecha de inicio:',
            'fecha_preaviso' => 'Fecha Preaviso',
            'dias_preaviso' => 'Dias Preaviso',
            'dias_contratados' => 'Dias Contratados',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contratos::className(), ['id_contrato' => 'id_contrato']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormatoContenido()
    {
        return $this->hasOne(FormatoContenido::className(), ['id_formato_contenido' => 'id_formato_contenido']);
    }
}
