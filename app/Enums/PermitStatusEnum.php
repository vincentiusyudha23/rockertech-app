<?php

namespace App\Enums;

enum PermitStatusEnum: int
{
    case PENDING = 1;
    case APPROVED = 3;
    case NOT_APPROVED = 2;

    public function labelStatus(): string
    {
        return match($this){
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::NOT_APPROVED => 'Not Approved',
        };
    }

    public function badgeStatus()
    {
        return match($this){
            self::PENDING => '<span class="badge badge-pill border-secondary border text-secondary m-0" style="scale: 0.85; background-color: rgba(108, 117, 125, 0.2);">'.$this->labelStatus().'</span>',
            self::APPROVED => '<span class="badge badge-pill border-success border text-success m-0" style="scale: 0.85; background-color: rgba(40, 167, 69, 0.2);">'.$this->labelStatus().'</span>',
            self::NOT_APPROVED => '<span class="badge badge-pill border-danger border text-danger m-0" style="scale: 0.85; background-color: rgba(220, 53, 69, 0.2);">'.$this->labelStatus().'</span>',
        };
    }
}