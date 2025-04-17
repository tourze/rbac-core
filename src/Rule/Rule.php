<?php

namespace Tourze\RBAC\Core\Rule;

interface Rule
{
    /**
     * @return string 规则名称
     */
    public function getTitle(): string;

    /**
     * 规则类型
     */
    public function getType(): RuleType;

    /**
     * 字段名
     */
    public function getColumn(): string;

    /**
     * 规则值
     */
    public function getValue(): mixed;
}
