<?php

namespace App\Enums;

enum Season : string
{
    case SPRING = 'spring';
    case SUMMER = 'summer';
    case AUTUMN = 'autumn';

    case WINTER = 'winter';


    public function next(): self
    {
        return match($this) {
            self::SPRING => self::SUMMER,
            self::SUMMER => self::AUTUMN,
            self::AUTUMN => self::WINTER,
            self::WINTER => self::SPRING,
        };
    }

    public function effect(): string
    {
        return match($this) {
            self::SPRING => '+1 level to Education',
            self::SUMMER => '+1 level to Health',
            self::AUTUMN => '+1 level to Safety',
            self::WINTER => '+1 level to Economy',
        };
    }



}
