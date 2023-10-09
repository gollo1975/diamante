<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_recibo_caja".
 *
 * @property int $id_tipo
 * @property string $concepto
 * @property int $resta
 * @property string $user_name
 */
class TipoReciboCaja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_recibo_caja';
    }
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
     
        $this->concepto = strtoupper($this->concepto); 
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['concepto'], 'required'],
            [['resta'], 'integer'],
            [['concepto'], 'string', 'max' => 30],
            [['user_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo' => 'Codigo',
            'concepto' => 'Concepto',
            'resta' => 'Resta',
            'user_name' => 'User Name',
        ];
    }
    public function getRestarecibo() {
        if($this->resta == 0){
            $restarecibo = "SI";
        }else{
            $restarecibo = "NO";
        }
        return $restarecibo;
    }
   

}
