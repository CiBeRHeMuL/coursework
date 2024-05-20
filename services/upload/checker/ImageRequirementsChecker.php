<?php

namespace app\services\upload\checker;

use app\services\upload\models\UploadedFile;
use Yii;

class ImageRequirementsChecker implements FileRequirementsCheckerInterface
{
    /**
     * @var string[] $errors
     */
    private array $errors = [];

    public function __construct(
        private readonly array|null $extensions = null,
        private readonly int|null $minFileSizeMbs = null,
        private readonly int|null $maxFileSizeMbs = null,
        private readonly int|null $minWidth = null,
        private readonly int|null $maxWidth = null,
        private readonly int|null $minHeight = null,
        private readonly int|null $maxHeight = null,
        private readonly float|null $ratio = null,
        private readonly string|null $ratioString = null,
    ) {
    }

    public function check(UploadedFile $file): bool
    {
        $this->errors = [];
        if ($this->extensions !== null && !in_array($file->getExtension(), $this->extensions, true)) {
            $this->errors[] = Yii::t('app', 'Неизвестное расширение');
            return false;
        } else {
            if ($this->minFileSizeMbs !== null && $this->minFileSizeMbs > $file->getSize()) {
                $this->errors[] = Yii::t('app', 'Файл должен быть больше {s} Мб', ['s' => $this->minFileSizeMbs]);
                return false;
            }
            if ($this->maxFileSizeMbs !== null && $this->maxFileSizeMbs < $file->getSize()) {
                $this->errors[] = Yii::t('app', 'Файл должен быть меньше {s} Мб', ['s' => $this->maxFileSizeMbs]);
                return false;
            }
            $imageInfo = getimagesize($file->getTmpName());
            if ($imageInfo === false) {
                $this->errors[] = Yii::t('app', 'Невозможно получить данные о файле');
            } else {
                [$width, $height] = $imageInfo;
                if ($width === 0 && $height === 0) {
                    $this->errors[] = Yii::t('app', 'Невозможно получить данные о файле');
                } else {
                    if ($this->minWidth !== null && $this->minWidth > $width) {
                        $this->errors[] = Yii::t(
                            'app',
                            'Ширина изображения должна быть больше {w, plural, one{# пикселя} many{# пикселей} other{# пикселей}}',
                            ['w' => $this->minWidth],
                        );
                    }
                    if ($this->maxWidth !== null && $this->maxWidth < $width) {
                        $this->errors[] = Yii::t(
                            'app',
                            'Ширина изображения должна быть меньше {w, plural, one{# пикселя} many{# пикселей} other{# пикселей}}',
                            ['w' => $this->maxWidth],
                        );
                    }
                    if ($this->minHeight !== null && $this->minHeight > $height) {
                        $this->errors[] = Yii::t(
                            'app',
                            'Высота изображения должна быть больше {w, plural, one{# пикселя} many{# пикселей} other{# пикселей}}',
                            ['w' => $this->minHeight],
                        );
                    }
                    if ($this->maxHeight !== null && $this->maxHeight < $height) {
                        $this->errors[] = Yii::t(
                            'app',
                            'Высота изображения должна быть меньше {w, plural, one{# пикселя} many{# пикселей} other{# пикселей}}',
                            ['w' => $this->maxHeight],
                        );
                    }
                    if ($this->ratio !== null && abs($this->ratio - $width / $height) > 0.01) {
                        $this->errors[] = Yii::t(
                            'app',
                            'Соотношение сторон должно быть {r}',
                            ['r' => $this->ratioString ?? $this->ratio],
                        );
                    }
                }
            }
        }
        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getLastError(): string|null
    {
        return $this->errors ? reset($this->errors) : null;
    }
}
