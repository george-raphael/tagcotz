<?php

namespace App\Enums;

enum  EventStatus: int
{
    case ACTIVE = 1;
    case OUTDATED = 0;

    function getLabel() : string {
           return str($this->name)->lower()->headline();
    }

    function getValue() : int {
        return $this->value;
 }
}
