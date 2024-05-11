<?php

namespace app\validators;

use Yii;
use yii\validators\Validator;

class TypeValidator extends Validator
{
    /**
     * Возможные типы валидируемых значений
     */
    private const VALIDATION_FUNCTIONS = [
        'bool' => 'is_bool',
        'boolean' => 'is_bool',
        'int' => 'is_int',
        'integer' => 'is_int',
        'long' => 'is_int',
        'float' => 'is_float',
        'double' => 'is_float',
        'real' => 'is_float',
        'number' => 'is_int || is_float && !is_nan',
        'finite-float' => 'is_float && is_finite',
        'finite-number' => 'is_int || is_float && is_finite',
        'numeric' => 'is_numeric',
        'string' => 'is_string',
        'scalar' => 'is_scalar',
        'array' => 'is_array',
        'iterable' => 'is_iterable',
        'countable' => 'is_countable',
        'callable' => 'is_callable',
        'object' => 'is_object',
        'resource' => 'is_resource',
        'null' => 'is_null',
        'alnum' => 'ctype_alnum',
        'alpha' => 'ctype_alpha',
        'cntrl' => 'ctype_cntrl',
        'digit' => 'ctype_digit',
        'graph' => 'ctype_graph',
        'lower' => 'ctype_lower',
        'print' => 'ctype_print',
        'punct' => 'ctype_punct',
        'space' => 'ctype_space',
        'upper' => 'ctype_upper',
        'xdigit' => 'ctype_xdigit',
    ];
    public string $type;

    public function init(): void
    {
        $this->message = Yii::t('app', 'Invalid value type');
    }

    protected function validateValue($value): array|null
    {
        $type = strtolower($this->type);
        if (isset(self::VALIDATION_FUNCTIONS[$type])) {
            $matched = match ($type) {
                'finite-float' => is_float($value) && is_finite($value),
                'finite-number' => is_int($value) || is_float($value) && is_finite($value),
                'number' => is_int($value) || is_float($value) && !is_nan($value),
                default => self::VALIDATION_FUNCTIONS[$type]($value),
            };
            if (!$matched) {
                return [$this->message, []];
            }
        } elseif (!($value instanceof $this->type)) {
            return [$this->message, []];
        }
        return null;
    }
}
