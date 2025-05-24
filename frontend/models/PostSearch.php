<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;
use common\models\Category; // For category_id filtering

/**
 * PostSearch represents the model behind the search form of `common\models\Post`.
 */
class PostSearch extends Post
{
    // No need to declare public properties for search if they exist in parent Post model
    // and are marked safe for search scenarios.
    // However, if you add attributes specific to search (e.g. a date range), declare them here.

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content'], 'safe'], // 'safe' allows them to be massively assigned and used in search
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
        $query = Post::find()->with(['author', 'category']); // Eager load related data

        // add conditions that should always apply here
        // For frontend, we usually only want to show active/published posts.
        // Assuming Post model has a 'status' attribute for this. If not, this can be omitted.
        // $query->andWhere(['status' => Post::STATUS_PUBLISHED]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10, // Adjust page size as needed
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]
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
            'category_id' => $this->category_id,
            'user_id' => $this->user_id, // Or search by author name if you add a public property for it
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
              ->andFilterWhere(['like', 'content', $this->content]);
        // Example: Filtering by category name if you had a 'categoryName' public property
        // if ($this->categoryName) {
        //     $query->joinWith(['category' => function ($q) {
        //         $q->where(['category.name' => $this->categoryName]);
        //     }]);
        // }


        return $dataProvider;
    }
}
