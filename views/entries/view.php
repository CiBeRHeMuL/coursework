<?php

use app\enum\EntryStatusEnum;
use app\enum\EntryTypeEnum;
use app\helpers\HHtml;
use app\models\Entry;
use yii\web\View;

/**
 * @var Entry $entry
 * @var View $this
 */

$this->title = $entry->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Новости'), 'url' => '/entries'];
$this->params['breadcrumbs'][] = ['label' => $entry->title, 'url' => ['/entries/view', 'id' => $entry->id]];
?>
<div class="row">
    <div class="col-lg-6 col-12">
        <div class="row">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <div class="h3"><?= $entry->title; ?></div>
                    <div class="<?= $entry->status->getTextColorClass()->getTextClass(); ?>" style="margin-top: -10px;">
                        <?= $entry->status->getName() . ($entry->status === EntryStatusEnum::Published
                            ? ' ' . HHtml::dateUi($entry->published_at)
                            : ''
                        ); ?>
                    </div>
                </div>
                <a class="btn <?= $entry->type->getTextColorClass()->getTextClass() ?>" href="/projects/view/<?= $entry->project_id ?>">
                    <?= match ($entry->type) {
                        EntryTypeEnum::ProjectLaunch => Yii::t('app', 'Запуск {p}', ['p' => $entry->project->name]),
                        EntryTypeEnum::ProjectUpdate => Yii::t('app', 'Обновление {p}', ['p' => $entry->project->name]),
                        EntryTypeEnum::ProjectWarning => Yii::t('app', 'Объявление на {p}', ['p' => $entry->project->name]),
                    }; ?></a>
            </div>
            <div class="col-12">
                <div class="border p-3 rounded-2"><?= $entry->text; ?></div>
            </div>
        </div>
    </div>
</div>
