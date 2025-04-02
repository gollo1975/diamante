<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prestaciones_sociales_adicion".
 *
 * @property int $id_adicion
 * @property int $id_prestacion
 * @property int $codigo_salario
 * @property int $tipo_adicion
 * @property int $valor_adicion
 * @property string $observacion
 * @property string $usuariosistema
 * @property string $fecha_creacion
 *
 * @property PrestacionesSociales $prestacion
 * @property ConceptoSalarios $codigoSalario
 */
class PrestacionesSocialesAdicion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prestaciones_sociales_adicion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_prestacion', 'codigo_salario', 'tipo_adicion', 'valor_adicion'], 'integer'],
            [['fecha_creacion'], 'safe'],
            [['observacion'], 'string', 'max' => 100],
            [['usuariosistema'], 'string', 'max' => 15],
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
            'id_adicion' => 'Id Adicion',
            'id_prestacion' => 'Id Prestacion',
            'codigo_salario' => 'Codigo Salario',
            'tipo_adicion' => 'Tipo Adicion',
            'valor_adicion' => 'Valor Adicion',
            'observacion' => 'Observacion',
            'usuariosistema' => 'Usuariosistema',
            'fecha_creacion' => 'Fecha Creacion',
        ];
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
