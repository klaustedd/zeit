<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Implantacao;

/**
 * ImplantacaoSearch represents the model behind the search form of `app\models\Implantacao`.
 */
class ImplantacaoSearch extends Implantacao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cadastrante_id', 'atendente_id', 'estado_implantacao_id'], 'integer'],
            [['data', 'responsavel', 'telefone', 'email_responsavel', 'celular', 'razao_social', 'cnpj', 'comentario'], 'safe'],
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
        $query = Implantacao::find();

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
            'id' => $this->id,
            'data' => $this->data,
            'cadastrante_id' => $this->cadastrante_id,
            'atendente_id' => $this->atendente_id,
            'estado_implantacao_id' => $this->estado_implantacao_id,
        ]);

        $query->andFilterWhere(['like', 'responsavel', $this->responsavel])
            ->andFilterWhere(['like', 'telefone', $this->telefone])
            ->andFilterWhere(['like', 'email_responsavel', $this->email_responsavel])
            ->andFilterWhere(['like', 'celular', $this->celular])
            ->andFilterWhere(['like', 'razao_social', $this->razao_social])
            ->andFilterWhere(['like', 'cnpj', $this->cnpj])
            ->andFilterWhere(['like', 'comentario', $this->comentario]);

        return $dataProvider;
    }
}
