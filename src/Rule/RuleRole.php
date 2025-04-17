<?php

namespace Tourze\RBAC\Core\Rule;

/**
 * 数据角色
 */
interface RuleRole
{
    /**
     * 返回数据范围定义
     *
     * @return array|Rule[]
     */
    public function getDataRules(): array;
}
