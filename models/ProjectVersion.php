<?php

namespace app\models;

use app\db\AbstractPgModel;
use app\validators\TypeValidator;
use DateTimeImmutable;
use Yii;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property int $project_id
 * @property bool $is_current
 * @property string $name
 * @property string $description
 * @property string $files_url
 * @property DateTimeImmutable $active_since
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 *
 * @property Project $project
 */
class ProjectVersion extends AbstractPgModel
{
    public static function tableName(): string
    {
        return 'project_version';
    }

    public function rules(): array
    {
        return [
            [['project_id', 'is_current', 'name', 'description', 'files_url', 'active_since'], 'required', 'skipOnEmpty' => false],
            [['project_id'], 'integer', 'skipOnEmpty' => false],
            [['project_id'], 'exist', 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            [['is_current'], 'default', 'value' => false],
            [['is_current'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['name', 'project_id'], 'unique', 'targetAttribute' => ['name', 'project_id']],
            [['name'], $this->validateName(...)],
            [['description'], 'string'],
            [['active_since'], TypeValidator::class, 'type' => DateTimeImmutable::class],
            [['active_since'], $this->validateActiveSince(...)],
            [['files_url'], 'url'],
        ];
    }

    public function validateName(): void
    {
        $projectVersions = $this->project->versions;
        if ($projectVersions !== [] && end($projectVersions)->name > $this->name) {
            $this->addError(
                'name',
                Yii::t('app', 'Нельзя добавить версию, меньшую последней ({v})', ['v' => end($this->project->versions)->name]),
            );
        }
    }

    public function validateActiveSince(): void
    {
        if ($this->is_current && $this->active_since->diff(new DateTimeImmutable())->invert === 1) {
            $this->addError('active_since', Yii::t('app', 'Для обновления проекта версия должна быть активной'));
        }
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', '#'),
            'project_id' => Yii::t('app', 'Проект'),
            'is_current' => Yii::t('app', 'Текущая'),
            'name' => Yii::t('app', 'Название'),
            'description' => Yii::t('app', 'Описание'),
            'files_url' => Yii::t('app', 'Ссылка на архив'),
            'active_since' => Yii::t('app', 'Активна с'),
            'created_at' => Yii::t('app', 'Создана'),
            'updated_at' => Yii::t('app', 'Обновлена'),
        ];
    }

    public function afterSave($insert, $changedAttributes): void
    {
        if ($this->is_current) {
            ProjectVersion::updateAll(['is_current' => false], ['AND', ['project_id' => $this->project_id], ['NOT', ['id' => $this->id]]]);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function getDateTimeFieldsType(): array
    {
        return [
            'created_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'updated_at' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
            'active_since' => [DateTimeImmutable::class, self::PROPERTY_DATETIME_FORMAT],
        ];
    }

    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }
}
