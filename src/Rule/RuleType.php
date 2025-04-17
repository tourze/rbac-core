<?php

namespace Tourze\RBAC\Core\Rule;

enum RuleType: string
{
    case EQUAL = 'equal';
    case LT = 'lt';
    case LTE = 'lte';
    case GT = 'gt';
    case GTE = 'gte';
}
