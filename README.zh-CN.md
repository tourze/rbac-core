# RBAC Core

[English](README.md) | [中文](README.zh-CN.md)

一个基于 PHP 8.1+ 的全面的基于角色的访问控制（RBAC）核心库实现。此库提供完整的 RBAC0、RBAC1 和 RBAC2 实现，并支持基于规则的扩展。

## 特性

- **RBAC0**：基础的基于角色的访问控制，包含用户、角色和权限
- **RBAC1**：支持角色层次结构和继承
- **RBAC2**：支持约束，包括互斥角色和前置角色
- **规则系统**：灵活的基于规则的权限评估
- **会话管理**：支持用户会话和角色激活
- **完整类型安全**：完整的 PHP 8.1+ 类型提示和接口

## 安装

```bash
composer require tourze/rbac-core
```

## 快速开始

### 基础用法

```php
<?php

use Tourze\RBAC\Core\Level0\User;
use Tourze\RBAC\Core\Level0\Role;
use Tourze\RBAC\Core\Level0\Permission;

// 在您的应用程序中实现接口
class MyUser implements User
{
    public function getRoles(): array
    {
        // 返回用户的角色
    }
    
    public function getSessions(): array
    {
        // 返回用户的会话
    }
}

class MyRole implements Role
{
    public function getName(): string
    {
        return 'admin';
    }
    
    public function getTitle(): string
    {
        return '管理员';
    }
}

class MyPermission implements Permission
{
    public function getName(): string
    {
        return 'user.create';
    }
    
    public function getTitle(): string
    {
        return '创建用户';
    }
    
    public function getParentPermission(): ?Permission
    {
        return null;
    }
}
```

### 层次化角色 (RBAC1)

```php
<?php

use Tourze\RBAC\Core\Level1\HierarchicalRole;

class MyHierarchicalRole implements HierarchicalRole
{
    public function getName(): string
    {
        return 'manager';
    }
    
    public function getTitle(): string
    {
        return '经理';
    }
    
    public function getParentRoles(): array
    {
        // 返回上级角色用于继承
        return [];
    }
}
```

### 约束角色 (RBAC2)

```php
<?php

use Tourze\RBAC\Core\Level2\MutexRole;
use Tourze\RBAC\Core\Level2\PreconditionRole;

// 互斥角色
class MyMutexRole implements MutexRole
{
    public function getMutexRoles(): array
    {
        // 返回不能同时分配的角色
        return [];
    }
}

// 前置角色
class MyPreconditionRole implements PreconditionRole
{
    public function getPreconditionRole(): ?Role
    {
        // 返回必须先分配的角色
        return null;
    }
}
```

### 基于规则的权限

```php
<?php

use Tourze\RBAC\Core\Rule\Rule;
use Tourze\RBAC\Core\Rule\RuleRole;

class MyRule implements Rule
{
    public function getName(): string
    {
        return 'business_hour_rule';
    }
    
    public function getTitle(): string
    {
        return '营业时间访问规则';
    }
    
    public function evaluate(array $context): bool
    {
        // 自定义规则逻辑
        $currentHour = date('H');
        return $currentHour >= 9 && $currentHour <= 17;
    }
}
```

## 架构

### RBAC 层级

- **Level 0 (RBAC0)**：核心组件 - 用户、角色、权限、会话
- **Level 1 (RBAC1)**：添加角色层次结构以支持继承
- **Level 2 (RBAC2)**：添加约束（互斥、前置条件）

### 核心组件

- `User`：表示系统用户，拥有角色和会话
- `Role`：定义用户角色及其权限
- `Permission`：定义系统权限及其层次结构
- `Session`：管理用户会话和激活的角色
- `Operation`：定义系统操作
- `Obj`：定义受保护的对象

## 测试

```bash
./vendor/bin/phpunit packages/rbac-core/tests
```

## 贡献

欢迎贡献！请随时提交 Pull Request。

## 许可证

该软件包是在 [MIT 许可证](LICENSE) 下发布的开源软件。

## 参考文档

1. [可能是史上最全的权限系统设计](https://zhuanlan.zhihu.com/p/73414693)
2. [详细了解RBAC（Role-Based Access Control）](https://zhuanlan.zhihu.com/p/513142061)
