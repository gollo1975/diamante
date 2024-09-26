<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pago_adicion_salario".
 *
 * @property int $id_pago_adicion
 * @property int $id_contrato
 * @property int $id_formato_contenido
 * @property int $valor_adicion
 * @property int $codigo_salario
 * @property string $fecha_aplicacion
 * @property string $fecha_proceso
 * @property string $user_name
 * @property int $estado_adicion
 *
 * @property Contratos $contrato
 * @property FormatoContenido $formatoContenido
 * @property ConceptoSalarios $codigoSalario
 */
class PagoAdicionSalario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pago_adicion_salario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_contrato', 'id_formato_contenido', 'valor_adicion', 'codigo_salario', 'estado_adicion'], 'integer'],
            [['id_formato_contenido', 'valor_adicion', 'codigo_salario', 'fecha_aplicacion'], 'required'],
            [['fecha_aplicacion', 'fecha_proceso'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contratos::className(), 'targetAttribute' => ['id_contrato' => 'id_contrato']],
            [['id_formato_contenido'], 'exist', 'skipOnError' => true, 'targetClass' => FormatoContenido::className(), 'targetAttribute' => ['id_formato_contenido' => 'id_formato_contenido']],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pago_adicion' => 'Id',
            'id_contrato' => 'Nro contrato:',
            'id_formato_contenido' => 'Formato de impresion:',
            'valor_adicion' => 'Valor adicional mensual:',
            'codigo_salario' => 'Codigo de salario:',
            'fecha_aplicacion' => 'Fecha aplicacion:',
            'fecha_proceso' => 'Fecha Proceso',
            'user_name' => 'User Name',
            'estado_adicion' => 'Estado Adicion',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoSalario()
    {
        return $this->hasOne(ConceptoSalarios::className(), ['codigo_salario' => 'codigo_salario']);
    }
}
