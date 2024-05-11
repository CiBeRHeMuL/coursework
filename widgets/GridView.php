<?php

namespace app\widgets;

use app\assets\GridViewAsset;
use Yii;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkSorter;

class GridView extends \yii\grid\GridView
{
    public $tableOptions = ['class' => 'table table-striped table-hover'];

    public array $tableBodyOptions = ['class' => ''];
    public $options = ['class' => 'table-container'];

    public $dataColumnClass = DataColumn::class;
    public $pager = [
        'class' => LinkPager::class,
    ];
    public $layout = '{header}{items}{footer}';
    public $sorter = [
        'class' => LinkSorter::class,
        'linkOptions' => ['class' => 'sorter-col__label'],
    ];

    public function run(): void
    {
        GridViewAsset::register($this->getView());
        parent::run();
    }

    public function renderSection($name): bool|string
    {
        return match ($name) {
            '{footer}' => $this->renderFooter(),
            '{header}' => $this->renderHeader(),
            default => parent::renderSection($name),
        };
    }

    public function renderItems(): string
    {
        $table = parent::renderItems();
        return Html::tag(
            'div',
            $table,
            ['class' => 'table-wrapper'],
        );
    }

    public function renderFooter(): string
    {
        return Html::tag(
            'div',
            $this->renderPager() . $this->renderSummary(),
            ['class' => 'table-footer'],
        );
    }

    public function renderHeader(): string
    {
        return Html::tag(
            'div',
            $this->renderPager() . $this->renderSummary(),
            ['class' => 'table-header'],
        );
    }

    public function renderTableBody(): string
    {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }

            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }

        if (empty($rows) && $this->emptyText !== false) {
            $colspan = count($this->columns);

            return Html::tag(
                'tbody',
                "\n<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>\n",
                $this->tableBodyOptions,
            );
        }

        return Html::tag(
            'tbody',
            implode("\n", $rows),
            $this->tableBodyOptions,
        );
    }

    public function renderSummary(): string
    {
        $count = $this->dataProvider->getCount();
        if ($count <= 0) {
            return '';
        }
        $summaryOptions = $this->summaryOptions;
        $tag = ArrayHelper::remove($summaryOptions, 'tag', 'div');
        if (($pagination = $this->dataProvider->getPagination()) !== false) {
            $totalCount = $this->dataProvider->getTotalCount();
            $begin = $pagination->getPage() * $pagination->pageSize + 1;
            $end = $begin + $count - 1;
            if ($begin > $end) {
                $begin = $end;
            }
            $page = $pagination->getPage() + 1;
            $pageCount = $pagination->pageCount;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag(
                    $tag,
                    Yii::t(
                        'yii',
                        '{begin, number}-{end, number} из {totalCount, number}',
                        [
                            'begin' => $begin,
                            'end' => $end,
                            'count' => $count,
                            'totalCount' => $totalCount,
                            'page' => $page,
                            'pageCount' => $pageCount,
                        ],
                    ),
                    $summaryOptions,
                );
            }
        } else {
            $begin = $page = $pageCount = 1;
            $end = $totalCount = $count;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', '{count, number}', [
                    'begin' => $begin,
                    'end' => $end,
                    'count' => $count,
                    'totalCount' => $totalCount,
                    'page' => $page,
                    'pageCount' => $pageCount,
                ]), $summaryOptions);
            }
        }

        if ($summaryContent === '') {
            return '';
        }

        return Html::tag($tag, Yii::$app->getI18n()->format($summaryContent, [
            'begin' => $begin,
            'end' => $end,
            'count' => $count,
            'totalCount' => $totalCount,
            'page' => $page,
            'pageCount' => $pageCount,
        ], Yii::$app->language), $summaryOptions);
    }
}
