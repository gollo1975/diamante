<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_rack_detalles".
 *
 * @property int $id
 * @property int $id_rack
 * @property int $id_posicion
 * @property int $descripcion
 *
 * @property TipoRack $rack
 * @property Posiciones $posicion
 */
class TipoRackDetalles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_rack_detalles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rack', 'id_posicion', 'descripcion'], 'required'],
            [['id_rack', 'id_posicion', 'descripcion'], 'integer'],
            [['id_rack'], 'exist', 'skipOnError' => true, 'targetClass' => TipoRack::className(), 'targetAttribute' => ['id_rack' => 'id_rack']],
            [['id_posicion'], 'exist', 'skipOnError' => true, 'targetClass' => Posiciones::className(), 'targetAttribute' => ['id_posicion' => 'id_posicion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_rack' => 'Id Rack',
            'id_posicion' => 'Id Posicion',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRack()
    {
        return $this->hasOne(TipoRack::className(), ['id_rack' => 'id_rack']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosicion()
    {
        return $this->hasOne(Posiciones::className(), ['id_posicion' => 'id_posicion']);
    }
}
