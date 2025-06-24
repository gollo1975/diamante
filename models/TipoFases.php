<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_fases".
 *
 * @property int $id_fase
 * @property string $nombre_fase
 *
 * @property ConfiguracionProducto[] $configuracionProductos
 */
class TipoFases extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_fases';
    }
    
     public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->nombre_fase = strtoupper($this->nombre_fase); 
        $this->color = strtoupper($this->color);
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_fase'], 'required'],
            [['nombre_fase'], 'string', 'max' => 40],
            [['color'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_fase' => 'Codigo',
            'nombre_fase' => 'Nombre de fase',
            'color' => 'Color',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguracionProductos()
    {
        return $this->hasMany(ConfiguracionProducto::className(), ['id_fase' => 'id_fase']);
    }
}
