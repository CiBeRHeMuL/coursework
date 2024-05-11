<?php

namespace app\models;

use app\db\AbstractPgModel;
use app\enum\EntryStatusEnum;
use app\enum\EntryTypeEnum;
use app\validators\TypeValidator;
use DateTimeImmutable;
use Yii;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $title
 * @property string|null $summary
 * @property string $text
 * @property EntryStatusEnum $status
 * @property EntryTypeEnum $type
 * @property int $project_id
 * @property DateTimeImmutable|null $published_at
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 *
 * @property Project $project
 */
class Entry extends AbstractPgModel
{
    public static function tableName(): string
    {
        return 'entry';
    }

    public function rules(): array
    {
        return [
            [['title', 'text', 'status', 'type', 'project_id'], 'required', 'message' => Yii::t('app', '{attribute} не может быть пустым')],
            [['title'], 'string', 'max' => 255, 'message' => Yii::t('app', 'Заголовок должен быть короче 255 символов')],
            [['text'], 'string'],
            [['status'], TypeValidator::class, 'type' => EntryStatusEnum::class],
            [['type'], TypeValidator::class, 'type' => EntryTypeEnum::class],
            [['status'], $this->validateStatus(...)],
            [['project_id'], 'integer'],
            [['project_id'], 'exist', 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            [['published_at'], 'default', 'value' => null],
            [['published_at'], $this->validatePublishedAt(...), 'skipOnEmpty' => false],
        ];
    }

    public function validateStatus()
    {
        if ($this->isAttributeChanged('status')) {
            $statuses = $this->getOldAttribute('status')?->getAvailableStatuses() ?? EntryStatusEnum::cases();
            if (!in_array($this->status, $statuses)) {
                $this->addError('status', Yii::t('app', 'Некорректный статус'));
            }
        }
    }

    public function validatePublishedAt(): void
    {
        if ($this->isAttributeChanged('status') && $this->status === EntryStatusEnum::Scheduled) {
            if ($this->published_at === null) {
                $this->addError('published_at', Yii::t('app', 'Необходимо заполнить дату публикации'));
            } elseif ($this->published_at->diff(new DateTimeImmutable())->invert === 0) {
                $this->addError('published_at', Yii::t('app', 'Дата публикации должна быть больше текущей'));
            }
        }
        if ($this->status === EntryStatusEnum::Published && !$this->published_at) {
            $this->addError('published_at', Yii::t('app', 'Необходимо заполнить дату публикации'));
        }
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', '#'),
            'title' => Yii::t('app', 'Заголовок'),
            'summary' => Yii::t('app', 'Краткое содержание'),
            'text' => Yii::t('app', 'Текст'),
            'status' => Yii::t('app', 'Статус'),
            'type' => Yii::t('app', 'Тип'),
            'project_id' => Yii::t('app', 'Проект'),
            'published_at' => Yii::t('app', 'Дата публикации'),
            'created_at' => Yii::t('app', 'Создана'),
            'updated_at' => Yii::t('app', 'Обновлена'),
        ];
    }

    public function beforeSave($insert): bool
    {
        if ($this->isAttributeChanged('status') && $this->status === EntryStatusEnum::Published) {
            $this->published_at = new DateTimeImmutable();
        }
        return parent::beforeSave($insert);
    }

    public function getFieldsEnum(): array
    {
        return [
            'status' => EntryStatusEnum::class,
            'type' => EntryTypeEnum::class,
        ];
    }

    public function getDateTimeFieldsType(): array
    {
        return [
            'created_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'updated_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'published_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
        ];
    }

    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }
}
