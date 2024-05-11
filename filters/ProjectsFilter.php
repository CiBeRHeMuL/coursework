<?php

namespace app\filters;

use app\data\Sort;
use app\enum\ProjectStatusEnum;
use app\models\Project;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class ProjectsFilter extends Model
{
    public string|null $name = null;
    public int|null $id = null;
    /** @var ProjectStatusEnum[]|null $statuses */
    public array|null $statuses = null;
    public int|null $agentId = null;

    public function search(): ActiveDataProvider
    {
        $query = Project::find()
            ->alias('t')
            ->with(['currentVersion'])
            ->filterWhere(['ILIKE', 't.name', $this->name])
            ->filterWhere(['status' => $this->statuses !== null ? array_map(fn(ProjectStatusEnum $s) => $s->value, $this->statuses) : null])
            ->andFilterWhere(['t.id' => $this->id, 't.agent_id' => $this->agentId]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 2],
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
        $query = Project::find()
            ->alias('t')
            ->filterWhere([
                'OR',
                ['ILIKE', 't.name', $this->name],
                ['ILIKE', new Expression('t.id::text'), $this->name],
            ])
            ->andFilterWhere(['t.id' => $this->id])
            ->asArray()
            ->select(['id', 'name']);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'attributes' => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }
}
