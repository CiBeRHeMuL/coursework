<?php

namespace app\models;

use app\db\AbstractPgModel;
use app\enum\ProjectStatusEnum;
use app\validators\TypeValidator;
use DateTimeImmutable;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property ProjectStatusEnum $status
 * @property int $agent_id
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 *
 * @property ProjectAgent $agent
 * @property ProjectVersion $currentVersion
 * @property ProjectVersion[] $versions
 */
class Project extends AbstractPgModel
{
    public string|null $update_to_version_id = null;

    public static function tableName(): string
    {
        return 'project';
    }

    public function rules(): array
    {
        return [
            [['name', 'description', 'status', 'agent_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['status'], TypeValidator::class, 'type' => ProjectStatusEnum::class],
            [['status'], $this->validateStatus(...)],
            [['agent_id'], 'integer'],
            [['agent_id'], 'exist', 'targetClass' => ProjectAgent::class, 'targetAttribute' => ['agent_id' => 'id']],
            [['update_to_version_id'], 'default', 'value' => null],
            [['update_to_version_id'], $this->validateUpdateToVersionId(...)],
        ];
    }

    public function validateUpdateToVersionId(): void
    {
        if (!ProjectVersion::find()->where(['project_id' => $this->id, 'id' => $this->update_to_version_id, 'is_current' => false])->exists()) {
            $this->addError('update_to_version_id', Yii::t('app', 'Нельзя обновить проект до этой версии'));
        }
    }

    public function validateStatus(): void
    {
        if ($this->versions === [] && $this->status === ProjectStatusEnum::Active) {
            $this->addError('status', Yii::t('app', 'Сначала необходимо добавить хотя бы одну версию'));
        }
        if ($this->isAttributeChanged('status')) {
            $statuses = $this->getOldAttribute('status')?->getAvailableStatuses() ?? ProjectStatusEnum::getDefaultAvailableStatuses();
            if (!in_array($this->status, $statuses)) {
                $this->addError('status', Yii::t('app', 'Некорректный статус'));
            }
        }
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', '#'),
            'name' => Yii::t('app', 'Название'),
            'description' => Yii::t('app', 'Описание'),
            'status' => Yii::t('app', 'Статус'),
            'agent_id' => Yii::t('app', 'Агент'),
            'created_at' => Yii::t('app', 'Создан'),
            'updated_at' => Yii::t('app', 'Обновлен'),
            'update_to_version_id' => Yii::t('app', 'Обновить или откатить до версии'),
        ];
    }

    public function afterSave($insert, $changedAttributes): void
    {
        if ($this->update_to_version_id !== null) {
            ProjectVersion::updateAll(
                ['is_current' => new Expression('id = :id', ['id' => $this->update_to_version_id])],
                ['project_id' => $this->id],
            );
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function getFieldsEnum(): array
    {
        return [
            'status' => ProjectStatusEnum::class,
        ];
    }

    public function getAgent(): ActiveQuery
    {
        return $this->hasOne(ProjectAgent::class, ['id' => 'agent_id']);
    }

    public function getCurrentVersion(): ActiveQuery
    {
        return $this->hasOne(ProjectVersion::class, ['project_id' => 'id'])
            ->andOnCondition(['is_current' => true])
            ->orderBy(['name' => SORT_ASC]);
    }

    public function getVersions(): ActiveQuery
    {
        return $this->hasMany(ProjectVersion::class, ['project_id' => 'id']);
    }
}
