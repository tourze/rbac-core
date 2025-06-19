<?php

namespace Tourze\RBAC\Core\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Tourze\RBAC\Core\Rule\RuleType;

class RuleTypeTest extends TestCase
{
    /**
     * 测试 RuleType 枚举的基本使用
     */
    public function testRuleType_basicUsage(): void
    {
        // 测试所有枚举值
        $this->assertEquals('equal', RuleType::EQUAL->value);
        $this->assertEquals('lt', RuleType::LT->value);
        $this->assertEquals('lte', RuleType::LTE->value);
        $this->assertEquals('gt', RuleType::GT->value);
        $this->assertEquals('gte', RuleType::GTE->value);
    }

    /**
     * 测试枚举值的比较
     */
    public function testRuleType_comparison(): void
    {
        $equalType = RuleType::EQUAL;
        $ltType = RuleType::LT;
        $lteType = RuleType::LTE;
        $gtType = RuleType::GT;
        $gteType = RuleType::GTE;

        // 测试同一枚举值的比较
        $this->assertSame($equalType, RuleType::EQUAL);
        $this->assertSame($ltType, RuleType::LT);
        $this->assertSame($lteType, RuleType::LTE);
        $this->assertSame($gtType, RuleType::GT);
        $this->assertSame($gteType, RuleType::GTE);

        // 测试不同枚举值的比较
        $this->assertNotSame($equalType, $ltType);
        $this->assertNotSame($ltType, $lteType);
        $this->assertNotSame($gtType, $gteType);
        $this->assertNotSame($equalType, $gtType);
    }

    /**
     * 测试将枚举转换为字符串
     */
    public function testRuleType_stringConversion(): void
    {
        $this->assertEquals('equal', RuleType::EQUAL->value);
        $this->assertEquals('lt', RuleType::LT->value);
        $this->assertEquals('lte', RuleType::LTE->value);
        $this->assertEquals('gt', RuleType::GT->value);
        $this->assertEquals('gte', RuleType::GTE->value);
    }

    /**
     * 测试从字符串创建枚举
     */
    public function testRuleType_fromString(): void
    {
        $equalType = RuleType::from('equal');
        $ltType = RuleType::from('lt');
        $lteType = RuleType::from('lte');
        $gtType = RuleType::from('gt');
        $gteType = RuleType::from('gte');

        $this->assertSame(RuleType::EQUAL, $equalType);
        $this->assertSame(RuleType::LT, $ltType);
        $this->assertSame(RuleType::LTE, $lteType);
        $this->assertSame(RuleType::GT, $gtType);
        $this->assertSame(RuleType::GTE, $gteType);
    }

    /**
     * 测试非法字符串值的异常处理
     */
    public function testRuleType_invalidString(): void
    {
        $this->expectException(\ValueError::class);
        RuleType::from('invalid_rule_type');
    }

    /**
     * 测试通过 tryFrom 方法处理非法字符串
     */
    public function testRuleType_tryFromWithInvalidString(): void
    {
        $result = RuleType::tryFrom('invalid_rule_type');
        $this->assertNull($result);
    }

    /**
     * 测试使用枚举进行条件语句
     */
    public function testRuleType_conditionalStatements(): void
    {
        // Test EQUAL case
        $equalResult = match (RuleType::EQUAL) {
            RuleType::EQUAL => 'equal',
            default => 'other'
        };
        $this->assertEquals('equal', $equalResult);

        // Test GT case  
        $gtResult = match (RuleType::GT) {
            RuleType::GT => 'greater than',
            default => 'other'
        };
        $this->assertEquals('greater than', $gtResult);

        // Test all enum values
        foreach (RuleType::cases() as $type) {
            $result = match ($type) {
                RuleType::EQUAL => 'equal',
                RuleType::LT => 'less than',
                RuleType::LTE => 'less than or equal',
                RuleType::GT => 'greater than',
                RuleType::GTE => 'greater than or equal',
            };
            $this->assertIsString($result);
        }
    }
}
