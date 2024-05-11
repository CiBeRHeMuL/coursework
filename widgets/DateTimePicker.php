<?php

namespace app\widgets;

class DateTimePicker extends \kartik\datetime\DateTimePicker
{
    public function init(): void
    {
        $value = $this->model->{$this->attribute} ?? null;
        if ($value instanceof \DateTimeInterface) {
            $value = $value->format('Y-m-d H:i');
        }
        $this->options['value'] = $value;
        parent::init();
    }
}
