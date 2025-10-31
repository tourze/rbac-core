# RBAC Core

[English](README.md) | [中文](README.zh-CN.md)

A comprehensive Role-Based Access Control (RBAC) core library implementation for PHP 8.1+. This library provides complete RBAC0, RBAC1, and RBAC2 implementations with rule-based extensions.

## Features

- **RBAC0**: Basic role-based access control with users, roles, and permissions
- **RBAC1**: Role hierarchy support with inheritance
- **RBAC2**: Constraints including mutual exclusion and prerequisite roles
- **Rule System**: Flexible rule-based permission evaluation
- **Session Management**: Support for user sessions with role activation
- **Full Type Safety**: Complete PHP 8.1+ type hints and interfaces

## Installation

```bash
composer require tourze/rbac-core
```

## Quick Start

### Basic Usage

```php
<?php

use Tourze\RBAC\Core\Level0\User;
use Tourze\RBAC\Core\Level0\Role;
use Tourze\RBAC\Core\Level0\Permission;

// Implement the interfaces in your application
class MyUser implements User
{
    public function getRoles(): array
    {
        // Return user's roles
    }
    
    public function getSessions(): array
    {
        // Return user's sessions
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
        return 'Administrator';
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
        return 'Create User';
    }
    
    public function getParentPermission(): ?Permission
    {
        return null;
    }
}
```

### Hierarchical Roles (RBAC1)

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
        return 'Manager';
    }
    
    public function getParentRoles(): array
    {
        // Return parent roles for inheritance
        return [];
    }
}
```

### Constrained Roles (RBAC2)

```php
<?php

use Tourze\RBAC\Core\Level2\MutexRole;
use Tourze\RBAC\Core\Level2\PreconditionRole;

// Mutually exclusive roles
class MyMutexRole implements MutexRole
{
    public function getMutexRoles(): array
    {
        // Return roles that cannot be assigned together
        return [];
    }
}

// Prerequisite roles
class MyPreconditionRole implements PreconditionRole
{
    public function getPreconditionRole(): ?Role
    {
        // Return role that must be assigned first
        return null;
    }
}
```

### Rule-Based Permissions

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
        return 'Business Hour Access Rule';
    }
    
    public function evaluate(array $context): bool
    {
        // Custom rule logic
        $currentHour = date('H');
        return $currentHour >= 9 && $currentHour <= 17;
    }
}
```

## Architecture

### RBAC Levels

- **Level 0 (RBAC0)**: Core components - Users, Roles, Permissions, Sessions
- **Level 1 (RBAC1)**: Adds role hierarchy for inheritance
- **Level 2 (RBAC2)**: Adds constraints (mutex, precondition)

### Core Components

- `User`: Represents system users with roles and sessions
- `Role`: Defines user roles with permissions
- `Permission`: Defines system permissions with hierarchy
- `Session`: Manages user sessions with activated roles
- `Operation`: Defines system operations
- `Obj`: Defines protected objects

## Testing

```bash
./vendor/bin/phpunit packages/rbac-core/tests
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## References

1. [可能是史上最全的权限系统设计](https://zhuanlan.zhihu.com/p/73414693)
2. [详细了解RBAC（Role-Based Access Control）](https://zhuanlan.zhihu.com/p/513142061)
