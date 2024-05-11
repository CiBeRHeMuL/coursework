<?php

namespace app\widgets;

use app\helpers\HDates;
use app\helpers\HHtml;
use app\helpers\HStrings;
use app\models\Project;
use Yii;
use yii\base\Widget;
use yii\bootstrap5\Html;
use yii\helpers\Url;

class ProjectCardWidget extends Widget
{
    public bool $withUpdateBtn = false;
    public Project $project;

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
                        Html::tag('h5', $this->project->name, ['class' => 'fs-5 m-0 text-truncate'])
                        . Html::tag(
                            'div',
                            HHtml::tooltipSpan(
                                Yii::t('app', 'Версия {v}', ['v' => $this->project->currentVersion->name]),
                                $this->project->currentVersion->active_since->format('Y-m-d H:i:s'),
                            ),
                            ['class' => 'text-muted w-auto', 'style' => 'font-size: 0.8rem; margin-top: -4px;'],
                        ),
                        ['class' => 'w-50'],
                    )
                    . Html::tag(
                        'div',
                        HHtml::tooltipSpan(
                            Yii::t('app', 'Обновлен {date}', ['date' => HDates::prettyUi($this->project->updated_at)]),
                            $this->project->updated_at->format('Y-m-d H:i:s'),
                        ),
                        ['class' => 'badge text-dark w-auto', 'style' => 'font-size: 0.8rem; margin-top: -4px;'],
                    ),
                    ['class' => 'card-title d-flex justify-content-between'],
                )
                . Html::tag('hr')
                . Html::tag('p', HStrings::crop($this->project->description, 200), ['class' => 'card-text'])
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
            Url::to("/projects/{$this->project->id}"),
            ['class' => 'btn btn-outline-primary'],
        );
        if ($this->withUpdateBtn) {
            $html .= Html::a(
                Yii::t('app', 'Обновить'),
                Url::to("/projects/{$this->project->id}/update"),
                ['class' => 'btn btn-outline-success'],
            );
        }
        return $html;
    }
}
