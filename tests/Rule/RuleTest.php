<?php

namespace Tourze\RBAC\Core\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\RBAC\Core\Rule\Rule;
use Tourze\RBAC\Core\Rule\RuleType;

/**
 * @internal
 */
#[CoversClass(Rule::class)]
final class RuleTest extends TestCase
{
    /**
     * 测试 Rule 接口的基本实现
     */
    public function testRuleBasicImplementation(): void
    {
        // 创建规则
        $rule = new class implements Rule {
            public function getTitle(): string
            {
                return '部门可见性规则';
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

        // 断言规则基本属性
        $this->assertEquals('部门可见性规则', $rule->getTitle());
        $this->assertEquals(RuleType::EQUAL, $rule->getType());
        $this->assertEquals('department_id', $rule->getColumn());
        $this->assertEquals(1, $rule->getValue());
    }

    /**
     * 测试不同类型的规则
     */
    public function testRuleDifferentTypes(): void
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
                return 'status';
            }

            public function getValue(): mixed
            {
                return 'active';
            }
        };

        // 创建小于类型规则
        $ltRule = new class implements Rule {
            public function getTitle(): string
            {
                return '小于规则';
            }

            public function getType(): RuleType
            {
                return RuleType::LT;
            }

            public function getColumn(): string
            {
                return 'price';
            }

            public function getValue(): mixed
            {
                return 100;
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
                return 'quantity';
            }

            public function getValue(): mixed
            {
                return 50;
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
                return 'age';
            }

            public function getValue(): mixed
            {
                return 18;
            }
        };

        // 创建大于等于类型规则
        $gteRule = new class implements Rule {
            public function getTitle(): string
            {
                return '大于等于规则';
            }

            public function getType(): RuleType
            {
                return RuleType::GTE;
            }

            public function getColumn(): string
            {
                return 'score';
            }

            public function getValue(): mixed
            {
                return 60;
            }
        };

        // 断言各种类型规则
        $this->assertEquals(RuleType::EQUAL, $equalRule->getType());
        $this->assertEquals('status', $equalRule->getColumn());
        $this->assertEquals('active', $equalRule->getValue());

        $this->assertEquals(RuleType::LT, $ltRule->getType());
        $this->assertEquals('price', $ltRule->getColumn());
        $this->assertEquals(100, $ltRule->getValue());

        $this->assertEquals(RuleType::LTE, $lteRule->getType());
        $this->assertEquals('quantity', $lteRule->getColumn());
        $this->assertEquals(50, $lteRule->getValue());

        $this->assertEquals(RuleType::GT, $gtRule->getType());
        $this->assertEquals('age', $gtRule->getColumn());
        $this->assertEquals(18, $gtRule->getValue());

        $this->assertEquals(RuleType::GTE, $gteRule->getType());
        $this->assertEquals('score', $gteRule->getColumn());
        $this->assertEquals(60, $gteRule->getValue());
    }

    /**
     * 测试不同值类型的规则
     */
    public function testRuleDifferentValueTypes(): void
    {
        // 测试字符串值的规则
        $stringValueRule = new class implements Rule {
            public function getTitle(): string
            {
                return '字符串值规则';
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

        // 测试整数值的规则
        $intValueRule = new class implements Rule {
            public function getTitle(): string
            {
                return '整数值规则';
            }

            public function getType(): RuleType
            {
                return RuleType::EQUAL;
            }

            public function getColumn(): string
            {
                return 'id';
            }

            public function getValue(): mixed
            {
                return 42;
            }
        };

        // 测试浮点数值的规则
        $floatValueRule = new class implements Rule {
            public function getTitle(): string
            {
                return '浮点数值规则';
            }

            public function getType(): RuleType
            {
                return RuleType::GT;
            }

            public function getColumn(): string
            {
                return 'price';
            }

            public function getValue(): mixed
            {
                return 99.99;
            }
        };

        // 测试布尔值的规则
        $boolValueRule = new class implements Rule {
            public function getTitle(): string
            {
                return '布尔值规则';
            }

            public function getType(): RuleType
            {
                return RuleType::EQUAL;
            }

            public function getColumn(): string
            {
                return 'is_active';
            }

            public function getValue(): mixed
            {
                return true;
            }
        };

        // 断言不同值类型的规则
        $this->assertIsString($stringValueRule->getValue());
        $this->assertEquals('active', $stringValueRule->getValue());

        $this->assertIsInt($intValueRule->getValue());
        $this->assertEquals(42, $intValueRule->getValue());

        $this->assertIsFloat($floatValueRule->getValue());
        $this->assertEquals(99.99, $floatValueRule->getValue());

        $this->assertIsBool($boolValueRule->getValue());
        $this->assertTrue($boolValueRule->getValue());
    }
}
