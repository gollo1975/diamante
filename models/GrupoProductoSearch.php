<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GrupoProducto;

/**
 * GrupoProductoSearch represents the model behind the search form of `app\models\GrupoProducto`.
 */
class GrupoProductoSearch extends GrupoProducto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_grupo','id_clasificacion'], 'integer'],
            [['nombre_grupo', 'fecha_registro', 'user_name'], 'safe'],
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
        $query = GrupoProducto::find();
       
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['nombre_grupo' => SORT_ASC]], // Agregar esta linea para agregar el orden por defecto
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_grupo' => $this->id_grupo,
            'fecha_registro' => $this->fecha_registro,
            
            'id_clasificacion' => $this->id_clasificacion,
        ]);

        $query->andFilterWhere(['like', 'nombre_grupo', $this->nombre_grupo])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['=', 'id_clasificacion', $this->id_clasificacion]);

        return $dataProvider;
    }
}
