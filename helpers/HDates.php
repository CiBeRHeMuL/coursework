<?php

namespace helpers;

use DateTimeImmutable;

class HDates
{
    public static function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }
}
