<?php

declare(strict_types=1);

namespace App\Enums;

enum LinkedListType: int
{
    case STRINGS_LINKED_LIST = 1;
    case INTEGERS_LINKED_LIST = 2;

    public function label(): string
    {
        return match ($this) {
            self::STRINGS_LINKED_LIST => __('Strings Linked List'),
            self::INTEGERS_LINKED_LIST => __('Integers Linked List'),
        };
    }

    public function textColor(): string
    {
        return match ($this) {
            self::STRINGS_LINKED_LIST => 'text-blue-700',
            self::INTEGERS_LINKED_LIST => 'text-green-700',
        };
    }
}
