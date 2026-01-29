# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Entrust is a Laravel package for Role-Based Access Control (RBAC). It provides flexible role and permission management through a three-model hierarchy: Users ↔ Roles ↔ Permissions (many-to-many relationships).

**Note:** This is a maintained fork of the abandoned `zizaco/entrust` package, upgraded to Laravel 9. For new projects, [spatie/laravel-permission](https://github.com/spatie/laravel-permission) is recommended.

## Commands

```bash
# Run tests
vendor/bin/phpunit

# Run a single test file
vendor/bin/phpunit tests/EntrustUserTest.php

# Run a specific test method
vendor/bin/phpunit --filter testHasRole
```

## Architecture

### Core Components

| Component | Location | Purpose |
|-----------|----------|---------|
| `Entrust` | `src/Entrust/Entrust.php` | Main service class (facade-backed) |
| Traits | `src/Entrust/Traits/` | Mixins for User, Role, Permission models |
| Contracts | `src/Entrust/Contracts/` | Interface definitions for extensibility |
| Middleware | `src/Entrust/Middleware/` | Route protection (role, permission, ability) |
| Config | `src/config/config.php` | Table names, model classes |

### Database Structure

Four tables linked by pivot tables:
- `roles`, `permissions` - Core entities
- `role_user` - Links users to roles
- `permission_role` - Links permissions to roles

### Key Traits

**EntrustUserTrait** (mix into User model):
- `hasRole()`, `can()`, `ability()` - Permission checks with caching
- `attachRole()`, `detachRole()` - Role management
- `cachedRoles()` - Performance caching layer

**EntrustRoleTrait** (mix into Role model):
- `hasPermission()`, `attachPermission()`, `detachPermission()`
- `cachedPermissions()` - Cached permission lookups

### Blade Directives

```blade
@role('admin') ... @endrole
@permission('edit_posts') ... @endpermission
@ability('admin', 'edit_posts') ... @endability
```

### Caching Strategy

- Roles cached per user: `entrust_roles_for_user_{id}`
- Permissions cached per role: `entrust_permissions_for_role_{id}`
- Auto-invalidation on model save/delete/restore via cache tags
- Graceful fallback when cache doesn't support tags

## Configuration

Published to `config/entrust.php`. Key settings:
- Model classes: `role`, `user`, `permission`
- Table names: `roles_table`, `permissions_table`, `role_user_table`, `permission_role_table`
- Foreign keys: `role_foreign_key`, `user_foreign_key`, `permission_foreign_key`

## Namespace

All classes under `Zizaco\Entrust\` namespace (preserved from original package for backwards compatibility).
