<?php

namespace App\Enums;

enum TodolistPriorityEnum: int
{
    case LOW = 1;
    case NORMAL = 2;
    case HIGH = 3;

    public function label(): string
    {
        return match($this){
            self::LOW => 'Low',
            self::NORMAL => 'Normal',
            self::HIGH => 'High'
        };
    }
}