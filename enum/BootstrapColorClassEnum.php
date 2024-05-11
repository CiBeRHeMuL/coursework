<?php

namespace app\enum;

enum BootstrapColorClassEnum: string
{
    case Success = 'success';
    case Warning = 'warning';
    case Danger = 'danger';
    case Primary = 'primary';
    case Secondary = 'secondary';

    public function getTextClass(): string
    {
        return "text-$this->value";
    }
}
