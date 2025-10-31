<?php

namespace Tourze\RBAC\Core\Rule;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum RuleType: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case EQUAL = 'equal';
    case LT = 'lt';
    case LTE = 'lte';
    case GT = 'gt';
    case GTE = 'gte';

    public function getLabel(): string
    {
        return match ($this) {
            self::EQUAL => '等于',
            self::LT => '小于',
            self::LTE => '小于等于',
            self::GT => '大于',
            self::GTE => '大于等于',
        };
    }
}
