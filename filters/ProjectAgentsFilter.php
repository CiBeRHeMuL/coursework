<?php

namespace app\filters;

use app\data\Sort;
use app\models\ProjectAgent;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class ProjectAgentsFilter extends Model
{
    public string|null $search = null;
    public string|null $name = null;
    public int|null $id = null;

    public function search(): ActiveDataProvider
    {
        $query = ProjectAgent::find()
            ->alias('t')
            ->filterWhere(['ILIKE', new Expression("concat(t.name, ' ', t.surname)"), $this->name])
            ->andFilterWhere(['t.id' => $this->id]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'attributes' => ['id', 'name'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
        return $provider;
    }

    public function filterSearch(): ActiveDataProvider
    {
        $query = ProjectAgent::find()
            ->alias('t')
            ->filterWhere([
                'OR',
                ['ILIKE', new Expression("concat(t.name, ' ', t.surname)"), $this->search],
                ['ILIKE', 't.phone', $this->search],
                ['ILIKE', 't.email', $this->search],
                ['ILIKE', 't.telegram', $this->search],
                ['ILIKE', new Expression('t.id::text'), $this->search],
            ])
            ->andFilterWhere(['t.id' => $this->id]);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'attributes' => ['id', 'name'],
                'defaultOrder' => ['name' => SORT_ASC, 'id' => SORT_ASC],
            ],
        ]);
    }
}
