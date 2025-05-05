<?php

namespace Tourze\RBAC\Core\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Tourze\RBAC\Core\Level0\Role;
use Tourze\RBAC\Core\Rule\Rule;
use Tourze\RBAC\Core\Rule\RuleRole;
use Tourze\RBAC\Core\Rule\RuleType;

class RuleRoleTest extends TestCase
{
    /**
     * 测试 RuleRole 接口的基本实现
     */
    public function testRuleRole_basicImplementation(): void
    {
        // 创建规则
        $departmentRule = new class implements Rule {
            public function getTitle(): string
            {
                return '部门规则';
            }

            public function getType(): RuleType
            {
                return RuleType::EQUAL;
            }

            public function getColumn(): string
            {
                return 'department_id';
            }

            public function getValue(): mixed
            {
                return 1;
            }
        };

        // 创建数据角色
        $ruleRole = new class($departmentRule) implements Role, RuleRole {
            private Rule $rule;

            public function __construct(Rule $rule)
            {
                $this->rule = $rule;
            }

            public function getName(): string
            {
                return 'department_admin';
            }

            public function getTitle(): string
            {
                return '部门管理员';
            }

            public function getDataRules(): array
            {
                return [$this->rule];
            }
        };

        // 断言数据角色属性
        $this->assertEquals('department_admin', $ruleRole->getName());
        $this->assertEquals('部门管理员', $ruleRole->getTitle());
        $this->assertCount(1, $ruleRole->getDataRules());
        $this->assertSame($departmentRule, $ruleRole->getDataRules()[0]);
        $this->assertEquals('部门规则', $ruleRole->getDataRules()[0]->getTitle());
        $this->assertEquals('department_id', $ruleRole->getDataRules()[0]->getColumn());
        $this->assertEquals(1, $ruleRole->getDataRules()[0]->getValue());
    }

    /**
     * 测试多规则的数据角色
     */
    public function testRuleRole_multipleRules(): void
    {
        // 创建部门规则
        $departmentRule = new class implements Rule {
            public function getTitle(): string
            {
                return '部门规则';
            }

            public function getType(): RuleType
            {
                return RuleType::EQUAL;
            }

            public function getColumn(): string
            {
                return 'department_id';
            }

            public function getValue(): mixed
            {
                return 1;
            }
        };

        // 创建状态规则
        $statusRule = new class implements Rule {
            public function getTitle(): string
            {
                return '状态规则';
            }

            public function getType(): RuleType
            {
                return RuleType::EQUAL;
            }

            public function getColumn(): string
            {
                return 'status';
            }

            public function getValue(): mixed
            {
                return 'active';
            }
        };

        // 创建多规则数据角色
        $ruleRole = new class($departmentRule, $statusRule) implements Role, RuleRole {
            private array $rules;

            public function __construct(Rule $departmentRule, Rule $statusRule)
            {
                $this->rules = [$departmentRule, $statusRule];
            }

            public function getName(): string
            {
                return 'active_department_admin';
            }

            public function getTitle(): string
            {
                return '活跃部门管理员';
            }

            public function getDataRules(): array
            {
                return $this->rules;
            }
        };

        // 断言多规则数据角色
        $this->assertEquals('active_department_admin', $ruleRole->getName());
        $this->assertEquals('活跃部门管理员', $ruleRole->getTitle());
        $this->assertCount(2, $ruleRole->getDataRules());

        $this->assertEquals('部门规则', $ruleRole->getDataRules()[0]->getTitle());
        $this->assertEquals('department_id', $ruleRole->getDataRules()[0]->getColumn());
        $this->assertEquals(1, $ruleRole->getDataRules()[0]->getValue());

        $this->assertEquals('状态规则', $ruleRole->getDataRules()[1]->getTitle());
        $this->assertEquals('status', $ruleRole->getDataRules()[1]->getColumn());
        $this->assertEquals('active', $ruleRole->getDataRules()[1]->getValue());
    }

    /**
     * 测试无规则的数据角色
     */
    public function testRuleRole_withNoRules(): void
    {
        // 创建无规则数据角色
        $ruleRole = new class implements Role, RuleRole {
            public function getName(): string
            {
                return 'unrestricted_admin';
            }

            public function getTitle(): string
            {
                return '无限制管理员';
            }

            public function getDataRules(): array
            {
                return [];
            }
        };

        // 断言无规则数据角色
        $this->assertEquals('unrestricted_admin', $ruleRole->getName());
        $this->assertEquals('无限制管理员', $ruleRole->getTitle());
        $this->assertEmpty($ruleRole->getDataRules());
    }

    /**
     * 测试不同类型规则的组合
     */
    public function testRuleRole_mixedRuleTypes(): void
    {
        // 创建等于类型规则
        $equalRule = new class implements Rule {
            public function getTitle(): string
            {
                return '等于规则';
            }

            public function getType(): RuleType
            {
                return RuleType::EQUAL;
            }

            public function getColumn(): string
            {
                return 'department_id';
            }

            public function getValue(): mixed
            {
                return 1;
            }
        };

        // 创建大于类型规则
        $gtRule = new class implements Rule {
            public function getTitle(): string
            {
                return '大于规则';
            }

            public function getType(): RuleType
            {
                return RuleType::GT;
            }

            public function getColumn(): string
            {
                return 'level';
            }

            public function getValue(): mixed
            {
                return 3;
            }
        };

        // 创建小于等于类型规则
        $lteRule = new class implements Rule {
            public function getTitle(): string
            {
                return '小于等于规则';
            }

            public function getType(): RuleType
            {
                return RuleType::LTE;
            }

            public function getColumn(): string
            {
                return 'experience';
            }

            public function getValue(): mixed
            {
                return 1000;
            }
        };

        // 创建混合规则类型的数据角色
        $ruleRole = new class($equalRule, $gtRule, $lteRule) implements Role, RuleRole {
            private array $rules;

            public function __construct(Rule $equalRule, Rule $gtRule, Rule $lteRule)
            {
                $this->rules = [$equalRule, $gtRule, $lteRule];
            }

            public function getName(): string
            {
                return 'complex_rule_role';
            }

            public function getTitle(): string
            {
                return '复杂规则角色';
            }

            public function getDataRules(): array
            {
                return $this->rules;
            }
        };

        // 断言混合规则类型的数据角色
        $this->assertEquals('complex_rule_role', $ruleRole->getName());
        $this->assertEquals('复杂规则角色', $ruleRole->getTitle());
        $this->assertCount(3, $ruleRole->getDataRules());

        $this->assertEquals(RuleType::EQUAL, $ruleRole->getDataRules()[0]->getType());
        $this->assertEquals('department_id', $ruleRole->getDataRules()[0]->getColumn());

        $this->assertEquals(RuleType::GT, $ruleRole->getDataRules()[1]->getType());
        $this->assertEquals('level', $ruleRole->getDataRules()[1]->getColumn());

        $this->assertEquals(RuleType::LTE, $ruleRole->getDataRules()[2]->getType());
        $this->assertEquals('experience', $ruleRole->getDataRules()[2]->getColumn());
    }
}
