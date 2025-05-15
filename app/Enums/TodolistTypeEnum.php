<?php

namespace App\Enums;

enum TodolistTypeEnum: int
{
    case EMPTY = 0;
    case CLIENT = 1;
    case DESIGN = 2;
    case CONTENT = 3;
    case CLOSING = 4;

    public function label(): string
    {
        return match($this){
            self::EMPTY => '',
            self::CLIENT => 'Closing',
            self::DESIGN => 'Design',
            self::CONTENT => 'Content',
            self::CLOSING => 'Closing'
        };
    }
}