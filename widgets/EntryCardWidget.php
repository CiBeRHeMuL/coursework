<?php

namespace app\widgets;

use app\helpers\HHtml;
use app\helpers\HStrings;
use app\models\Entry;
use Yii;
use yii\base\Widget;
use yii\bootstrap5\Html;
use yii\helpers\Url;

class EntryCardWidget extends Widget
{
    public bool $withUpdateBtn = false;
    public bool $withProjectBtn = false;
    public Entry $entry;

    public function run(): string
    {
        return $this->renderCard();
    }

    private function renderCard(): string
    {
        $btnHtml = $this->getBtnHtml();
        return Html::tag(
            'div',
            Html::tag(
                'div',
                Html::tag(
                    'div',
                    Html::tag(
                        'div',
                        Html::tag('h5', $this->entry->title, ['class' => 'fs-5 m-0 text-truncate'])
                        . Html::tag(
                            'div',
                            HHtml::dateUi($this->entry->published_at),
                            ['class' => 'text-muted w-auto', 'style' => 'font-size: 0.8rem; margin-top: -4px;'],
                        ),
                        ['class' => 'w-50'],
                    )
                    . Html::tag(
                        'div',
                        $this->entry->type->getName(),
                        ['class' => "badge {$this->entry->type->getTextColorClass()->getTextClass()}"],
                    ),
                    ['class' => 'card-title d-flex justify-content-between'],
                )
                . Html::tag('hr')
                . Html::tag('p', HStrings::crop($this->entry->summary, 100), ['class' => 'card-text'])
                . Html::tag('div', $btnHtml, ['class' => 'btn-group']),
                ['class' => 'card-body'],
            ),
            ['class' => 'card'],
        );
    }

    private function getBtnHtml(): string
    {
        $html = Html::a(
            Yii::t('app', 'Подробнее'),
            Url::to("/entries/{$this->entry->id}"),
            ['class' => 'btn btn-outline-primary'],
        );
        if ($this->withProjectBtn) {
            $html .= Html::a(
                Yii::t('app', 'Проект'),
                Url::to("/projects/{$this->entry->project_id}"),
                ['class' => 'btn btn-success'],
            );
        }
        if ($this->withUpdateBtn) {
            $html .= Html::a(
                Yii::t('app', 'Обновить'),
                Url::to("/entries/{$this->entry->project_id}/update"),
                ['class' => 'btn btn-outline-success'],
            );
        }
        return $html;
    }
}
