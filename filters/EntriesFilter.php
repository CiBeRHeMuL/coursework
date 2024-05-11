<?php

namespace app\filters;

use app\data\Sort;
use app\enum\EntryStatusEnum;
use app\models\Entry;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EntriesFilter extends Model
{
    /**
     * @var EntryStatusEnum[]|null $statuses
     */
    public array|null $statuses = [];
    public int|null $projectId = null;

    public function search(): ActiveDataProvider
    {
        $query = Entry::find()
            ->with('project')
            ->filterWhere([
                'status' => $this->statuses !== null ? array_map(fn(EntryStatusEnum $s) => $s->value, $this->statuses) : null,
                'project_id' => $this->projectId,
            ]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'attributes' => ['id', 'title', 'status', 'type', 'project_id'],
                'defaultOrder' => ['project_id' => SORT_DESC, 'id' => SORT_DESC],
            ],
        ]);
        return $provider;
    }
}
