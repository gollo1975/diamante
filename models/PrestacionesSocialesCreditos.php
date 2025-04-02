<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prestaciones_sociales_creditos".
 *
 * @property int $id
 * @property int $id_credito
 * @property int $id_prestacion
 * @property int $codigo_salario
 * @property int $valor_credito
 * @property int $saldo_credito
 * @property int $deduccion
 * @property string $fecha_inicio
 * @property string $fecha_creacion
 * @property int $estado_cerrado
 * @property string $usuariosistema
 *
 * @property Credito $credito
 * @property PrestacionesSociales $prestacion
 * @property ConceptoSalarios $codigoSalario
 */
class PrestacionesSocialesCreditos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prestaciones_sociales_creditos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_credito', 'id_prestacion', 'codigo_salario', 'valor_credito', 'saldo_credito', 'deduccion', 'estado_cerrado'], 'integer'],
            [['fecha_inicio', 'fecha_creacion'], 'safe'],
            [['usuariosistema'], 'string', 'max' => 15],
            [['id_credito'], 'exist', 'skipOnError' => true, 'targetClass' => Credito::className(), 'targetAttribute' => ['id_credito' => 'id_credito']],
            [['id_prestacion'], 'exist', 'skipOnError' => true, 'targetClass' => PrestacionesSociales::className(), 'targetAttribute' => ['id_prestacion' => 'id_prestacion']],
            [['codigo_salario'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoSalarios::className(), 'targetAttribute' => ['codigo_salario' => 'codigo_salario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_credito' => 'Id Credito',
            'id_prestacion' => 'Id Prestacion',
            'codigo_salario' => 'Codigo Salario',
            'valor_credito' => 'Valor Credito',
            'saldo_credito' => 'Saldo Credito',
            'deduccion' => 'Deduccion',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_creacion' => 'Fecha Creacion',
            'estado_cerrado' => 'Estado Cerrado',
            'usuariosistema' => 'Usuariosistema',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredito()
    {
        return $this->hasOne(Credito::className(), ['id_credito' => 'id_credito']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrestacion()
    {
        return $this->hasOne(PrestacionesSociales::className(), ['id_prestacion' => 'id_prestacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodigoSalario()
    {
        return $this->hasOne(ConceptoSalarios::className(), ['codigo_salario' => 'codigo_salario']);
    }
}
