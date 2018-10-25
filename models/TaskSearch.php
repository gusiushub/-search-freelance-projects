<?php

namespace app\models;

//use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use app\models\Task;

/**
 * TaskSearch represents the model behind the search form of `app\models\Task`.
 */
class TaskSearch extends Task
{
    public $min_price;
    public $max_price;
    public $categories_id;
    public $check_price;
    public $check_time1;
    public $check_time3;
    public $check_time6;
    public $check_time7dn;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[  'id','categories_id', 'site_id', 'price',
                'min_price','max_price',
                'check_time1','time_unix','check_time3',
                'check_time6','check_time7dn','check_price'],
                'integer'],
            [['title', 'date', 'text', 'status','subcategories_id',
                'check_time1','check_time3','check_time6','check_time7dn'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'check_time1'       => 'За последний час',
            'check_time3'       => 'За последние 3 часа',
            'check_time6'       => 'За последние 6 часов',
            'check_time7dn'     => 'За неделю',
            'min_price'         => 'Минимальная цена',
            'subcategories_id'  => 'Подкатегория',
            'categories_id'     => 'Категория',
            'title'             => 'Ключевое слово',
            'site_id'           => 'Выбрать сайт',
            'check_price'       => 'По договоренности',
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
        $query = Task::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'site_id' => $this->site_id,
            'date' => $this->date,
            'subcategories_id' => $this->subcategories_id,
            'check_time1' => $this->time_unix,
        ]);

        if ($this->min_price!='') {
            $query  ->andFilterWhere(['like', 'title', $this->title])
                    ->andFilterWhere(['like', 'categories_id', $this->categories_id])
                    ->andFilterWhere(['like', 'subcategories_id', $this->subcategories_id])
                    ->andFilterWhere(['>=', 'price', $this->min_price])
                    ->andFilterWhere(['>=', 'time_unix', $this->check_time1])
                    ->andFilterWhere(['>=', 'time_unix', $this->check_time3])
                    ->andFilterWhere(['>=', 'time_unix', $this->check_time6])
                    ->andFilterWhere(['>=', 'time_unix', $this->check_time7dn]);
        }else{
            if ($this->check_price==1){
                $check_price = 0;
            }
            $query  ->andFilterWhere(['like', 'title', $this->title])
                    ->andFilterWhere(['like', 'categories_id', $this->categories_id])
                    ->andFilterWhere(['like', 'subcategories_id', $this->subcategories_id])
                    ->andFilterWhere(['>=', 'time_unix', $this->check_time1])
                    ->andFilterWhere(['>=', 'time_unix', $this->check_time3])
                    ->andFilterWhere(['>=', 'time_unix', $this->check_time6])
                    ->andFilterWhere(['like', 'price', $check_price])
                    ->andFilterWhere(['>=', 'time_unix', $this->check_time7dn]);
        }

        return $dataProvider;
    }
}
