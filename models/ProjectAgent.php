<?php

namespace app\models;

use app\db\AbstractPgModel;
use app\enum\PreferredAgentCommunicationMethodEnum;
use app\validators\TypeValidator;
use borales\extensions\phoneInput\PhoneInputValidator;
use DateTimeImmutable;
use Yii;

/**
 * @property int $id
 * @property string $name
 * @property string|null $surname
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $telegram
 * @property PreferredAgentCommunicationMethodEnum $preferred_communication_method
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 *
 * @property string $fullName
 * @property string $preferredContact
 * @property string $contactName
 * @property string $htmlContactName
 * @property string|null $htmlPhone
 * @property string|null $htmlEmail
 * @property string|null $htmlTelegram
 * @property string $htmlPreferredContact
 */
class ProjectAgent extends AbstractPgModel
{
    public static function tableName(): string
    {
        return 'project_agent';
    }

    public function rules(): array
    {
        return [
            [['name', 'preferred_communication_method'], 'required'],
            [['preferred_communication_method'], TypeValidator::class, 'type' => PreferredAgentCommunicationMethodEnum::class],
            [['name', 'surname', 'email'], 'string', 'max' => 255],
            [['surname', 'email', 'phone', 'telegram'], 'default', 'value' => null],
            [['phone'], 'string', 'max' => 15],
            [['telegram'], 'string', 'max' => 32],
            [['phone'], PhoneInputValidator::class],
            [['email'], 'email'],
            [['telegram'], 'match', 'pattern' => '/^[a-zA-Z0-9-_]{5,32}$/'],
            [['telegram'], $this->validateContacts(...), 'skipOnEmpty' => false],
            [['phone', 'email', 'telegram'], 'unique'],
            [['avatar'], 'url'],
        ];
    }

    public function validateContacts(): void
    {
        if (!$this->phone && !$this->telegram && !$this->email) {
            $this->addError('phone', Yii::t('app', 'Необходимо заполнить один из способов связи'));
            $this->addError('email', Yii::t('app', 'Необходимо заполнить один из способов связи'));
            $this->addError('telegram', Yii::t('app', 'Необходимо заполнить один из способов связи'));
        } else {
            $requiredContact = match ($this->preferred_communication_method) {
                PreferredAgentCommunicationMethodEnum::Phone => 'phone',
                PreferredAgentCommunicationMethodEnum::Email => 'email',
                PreferredAgentCommunicationMethodEnum::Telegram => 'telegram',
            };
            if (!$this->$requiredContact) {
                $this->addError(
                    $requiredContact,
                    Yii::t('app', 'Необходимо заполнить этот контакт, так как вы выбрали его в качестве основного'),
                );
            }
        }
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', '#'),
            'name' => Yii::t('app', 'Имя'),
            'surname' => Yii::t('app', 'Фамилия'),
            'phone' => Yii::t('app', 'Телефон'),
            'email' => Yii::t('app', 'Почта'),
            'telegram' => Yii::t('app', 'Телеграм'),
            'preferred_communication_method' => Yii::t('app', 'Предпочтительный метод связи'),
            'created_at' => Yii::t('app', 'Создан'),
            'updated_at' => Yii::t('app', 'Обновлен'),
        ];
    }

    public function getFieldsEnum(): array
    {
        return [
            'preferred_communication_method' => PreferredAgentCommunicationMethodEnum::class,
        ];
    }

    public function getFullName(): string
    {
        return trim("$this->name $this->surname");
    }

    public function getHtmlPhone(): string|null
    {
        return $this->phone
            ? $this->getHtmlContact($this->phone, PreferredAgentCommunicationMethodEnum::Phone)
            : null;
    }

    public function getHtmlEmail(): string|null
    {
        return $this->email
            ? $this->getHtmlContact($this->email, PreferredAgentCommunicationMethodEnum::Email)
            : null;
    }

    public function getHtmlTelegram(): string|null
    {
        return $this->telegram
            ? $this->getHtmlContact($this->telegram, PreferredAgentCommunicationMethodEnum::Telegram, '@')
            : null;
    }

    public function getPreferredContact(): string
    {
        return match ($this->preferred_communication_method) {
            PreferredAgentCommunicationMethodEnum::Phone => $this->phone,
            PreferredAgentCommunicationMethodEnum::Email => $this->email,
            PreferredAgentCommunicationMethodEnum::Telegram => $this->telegram,
        };
    }

    public function getHtmlPreferredContact(): string
    {
        $prefix = '';
        if ($this->preferred_communication_method === PreferredAgentCommunicationMethodEnum::Telegram) {
            $prefix = '@';
        }
        return $this->getHtmlContact($this->preferredContact, $this->preferred_communication_method, $prefix);
    }

    public function getContactName(): string
    {
        $prefix = '';
        if ($this->preferred_communication_method === PreferredAgentCommunicationMethodEnum::Telegram) {
            $prefix = '@';
        }
        return "$this->fullName (" . $this->preferred_communication_method->getShortName() . ": $prefix$this->preferredContact)";
    }

    public function getHtmlContactName(): string
    {
        return "$this->fullName (" . $this->preferred_communication_method->getShortName() . ": $this->htmlPreferredContact)";
    }

    private function getHtmlContact(string $contact, PreferredAgentCommunicationMethodEnum $method, string $prefix = ''): string
    {
        return '<a href="' . $method->getLinkPrefix() . $contact . '" target="_blank" style="font-size: inherit;">' . $prefix . $contact . '</a>';
    }
}
