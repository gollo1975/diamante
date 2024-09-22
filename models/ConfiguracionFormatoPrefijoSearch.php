<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConfiguracionFormatoPrefijo;

/**
 * ConfiguracionFormatoPrefijoSearch represents the model behind the search form of `app\models\ConfiguracionFormatoPrefijo`.
 */
class ConfiguracionFormatoPrefijoSearch extends ConfiguracionFormatoPrefijo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_configuracion_prefijo', 'estado_formato'], 'integer'],
            [['formato'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ConfiguracionFormatoPrefijo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_configuracion_prefijo' => $this->id_configuracion_prefijo,
            'estado_formato' => $this->estado_formato,
        ]);

        $query->andFilterWhere(['like', 'formato', $this->formato]);

        return $dataProvider;
    }
}
