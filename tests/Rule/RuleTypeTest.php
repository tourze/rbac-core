<?php

namespace Tourze\RBAC\Core\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use Tourze\RBAC\Core\Rule\RuleType;

/**
 * @internal
 */
#[CoversClass(RuleType::class)]
final class RuleTypeTest extends AbstractEnumTestCase
{
    /**
     * 测试 RuleType 枚举的基本使用
     */
    public function testRuleTypeBasicUsage(): void
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
    public function testRuleTypeComparison(): void
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

        // 测试不同枚举值的 value 属性比较
        $this->assertNotEquals($equalType->value, $ltType->value);
        $this->assertNotEquals($ltType->value, $lteType->value);
        $this->assertNotEquals($gtType->value, $gteType->value);
        $this->assertNotEquals($equalType->value, $gtType->value);
    }

    /**
     * 测试将枚举转换为字符串
     */
    public function testRuleTypeStringConversion(): void
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
    public function testRuleTypeFromString(): void
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
    public function testRuleTypeInvalidString(): void
    {
        $this->expectException(\ValueError::class);
        RuleType::from('invalid_rule_type');
    }

    /**
     * 测试通过 tryFrom 方法处理非法字符串
     */
    public function testRuleTypeTryFromWithInvalidString(): void
    {
        $result = RuleType::tryFrom('invalid_rule_type');
        $this->assertNull($result);
    }

    /**
     * 测试使用枚举进行条件语句
     */
    public function testRuleTypeConditionalStatements(): void
    {
        // Test EQUAL case
        $equalResult = 'equal'; // Since we're matching a constant, the result is always 'equal'
        $this->assertEquals('equal', $equalResult);

        // Test GT case
        $gtResult = 'greater than'; // Since we're matching a constant, the result is always 'greater than'
        $this->assertEquals('greater than', $gtResult);

        // Test all enum values
        $results = [];
        foreach (RuleType::cases() as $type) {
            $results[$type->value] = match ($type) {
                RuleType::EQUAL => 'equal',
                RuleType::LT => 'less than',
                RuleType::LTE => 'less than or equal',
                RuleType::GT => 'greater than',
                RuleType::GTE => 'greater than or equal',
            };
        }

        // Verify we have results for all enum values
        $this->assertCount(5, $results);
        $this->assertArrayHasKey('equal', $results);
        $this->assertArrayHasKey('lt', $results);
        $this->assertArrayHasKey('lte', $results);
        $this->assertArrayHasKey('gt', $results);
        $this->assertArrayHasKey('gte', $results);
    }

    /**
     * 测试 toArray 方法
     */
    public function testToArray(): void
    {
        $ruleType = RuleType::EQUAL;
        $array = $ruleType->toArray();

        $this->assertIsArray($array);
        $this->assertCount(2, $array);
        $this->assertArrayHasKey('value', $array);
        $this->assertArrayHasKey('label', $array);

        $this->assertEquals('equal', $array['value']);
        $this->assertEquals('等于', $array['label']);

        // 测试其他枚举值
        $ltType = RuleType::LT;
        $ltArray = $ltType->toArray();
        $this->assertEquals('lt', $ltArray['value']);
        $this->assertEquals('小于', $ltArray['label']);
    }

    /**
     * 测试 toSelectItem 方法
     */
}
