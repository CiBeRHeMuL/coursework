<?php

namespace app\filters;

use app\data\Sort;
use app\models\ProjectVersion;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProjectVersionsFilter extends Model
{
    public int|null $projectId = null;
    public int|null $id = null;
    public string|null $name = null;

    public function filterSearch(): ActiveDataProvider
    {
        $query = ProjectVersion::find()
            ->alias('t')
            ->andFilterWhere(['t.id' => $this->id, 'project_id' => $this->projectId])
            ->andFilterWhere(['ILIKE', 't.name', $this->name]);
        if ($this->projectId !== null) {
            $query
                ->with('project')
                ->andWhere(['t.is_current' => false]);
        }
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'attributes' => ['id', 'project_id', 'name'],
                'defaultOrder' => ['project_id' => SORT_ASC, 'name' => SORT_DESC],
            ],
        ]);
    }
}
