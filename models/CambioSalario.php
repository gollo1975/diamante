<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cambio_salario".
 *
 * @property int $id_cambio_salario
 * @property int $salario_anterior
 * @property int $nuevo_salario
 * @property string $fecha_aplicacion
 * @property int $id_contrato
 * @property int $id_formato_contenido
 * @property string $user_name
 * @property string $fecha_creacion
 * @property string $observacion
 *
 * @property Contratos $contrato
 * @property FormatoContenido $formatoContenido
 */
class CambioSalario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cambio_salario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['salario_anterior', 'nuevo_salario', 'id_contrato', 'id_formato_contenido'], 'integer'],
            [['nuevo_salario', 'fecha_aplicacion', 'id_formato_contenido'], 'required'],
            [['fecha_aplicacion', 'fecha_creacion'], 'safe'],
            [['user_name'], 'string', 'max' => 15],
            [['observacion'], 'string', 'max' => 100],
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
            'id_cambio_salario' => 'Codigo:',
            'salario_anterior' => 'Salario anterior:',
            'nuevo_salario' => 'Nuevo salario:',
            'fecha_aplicacion' => 'Fecha aplicacion:',
            'id_contrato' => 'Nro de contrato:',
            'id_formato_contenido' => 'Formato de impresión:',
            'user_name' => 'User name',
            'fecha_creacion' => 'Fecha creacion:',
            'observacion' => 'Observacion:',
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
