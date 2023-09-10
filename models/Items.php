<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "items".
 *
 * @property int $id_items
 * @property string $descripcion
 */
class Items extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'items';
    }
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->descripcion = strtoupper($this->descripcion); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion','id_iva'], 'required'],
            [['descripcion'], 'string', 'max' => 40],
            [['id_iva'],'integer'],
            [['id_iva'], 'exist', 'skipOnError' => true, 'targetClass' => ConfiguracionIva::className(), 'targetAttribute' => ['id_iva' => 'id_iva']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_items' => 'Id',
            'id_iva' => 'Iva',
            'descripcion' => 'Descripcion',
        ];
    }
    
    public function getIva()
    {
        return $this->hasOne(ConfiguracionIva::className(), ['id_iva' => 'id_iva']);
    }
}
