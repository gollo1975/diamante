<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_contrato".
 *
 * @property int $id_tipo_contrato
 * @property string $contrato
 * @property int $prorroga
 * @property int $numero_prorrogas
 * @property string $prefijo
 * @property int $id_configuracion_prefijo
 * @property string $abreviatura
 * @property int $estado
 * @property string $user_name
 *
 * @property ConfiguracionFormatoPrefijo $configuracionPrefijo
 */
class TipoContrato extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_contrato';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contrato', 'prefijo'], 'required'],
            [['prorroga', 'numero_prorrogas', 'id_configuracion_prefijo', 'estado'], 'integer'],
            [['contrato'], 'string', 'max' => 100],
            [['prefijo'], 'string', 'max' => 3],
            [['abreviatura'], 'string', 'max' => 10],
            [['user_name'], 'string', 'max' => 15],
            [['id_configuracion_prefijo'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionFormatoPrefijo::className(), 'targetAttribute' => ['id_configuracion_prefijo' => 'id_configuracion_prefijo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_contrato' => 'Id Tipo Contrato',
            'contrato' => 'Contrato',
            'prorroga' => 'Prorroga',
            'numero_prorrogas' => 'Numero Prorrogas',
            'prefijo' => 'Prefijo',
            'id_configuracion_prefijo' => 'Id Configuracion Prefijo',
            'abreviatura' => 'Abreviatura',
            'estado' => 'Estado',
            'user_name' => 'User Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracionPrefijo()
    {
        return $this->hasOne(ConfiguracionFormatoPrefijo::className(), ['id_configuracion_prefijo' => 'id_configuracion_prefijo']);
    }
}
