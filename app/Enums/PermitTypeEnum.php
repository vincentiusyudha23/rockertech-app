<?php

namespace App\Enums;

enum PermitTypeEnum: int
{
    case SICK = 1;
    case LEAVE = 2;

    public function labelType(): string
    {
        return match($this){
            self::SICK => 'Sick',
            self::LEAVE => 'Leave'
        };
    }

    public function badgeType()
    {
        return match($this) {
            self::SICK => '<span class="badge badge-pill bg-gradient-warning m-0">'.$this->labelType().'</span>',
            self::LEAVE => '<span class="badge badge-pill bg-gradient-info m-0">'.$this->labelType().'</span>'
        };
    }
}