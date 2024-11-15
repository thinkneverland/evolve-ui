# EvolveUI: Free Extension for EvolveCore

EvolveUI is a **free plugin** designed to enhance and complement the **EvolveCore** package. It provides a user-friendly interface and streamlined tools to make managing your models and data easier than ever.

> **Note:** EvolveUI requires the **EvolveCore** package to function.  
> [Get EvolveCore](https://evolve.thinkneverland.com) today and unlock the full potential of your Laravel applications.

### Why Use EvolveUI?
- Simplify CRUD operations with pre-built views and forms.
- Integrate seamlessly with EvolveCore for validation, filtering, and sorting.
- Customize your UI with published templates and configuration options.

**Visit [evolve.thinkneverland.com](https://evolve.thinkneverland.com) to learn more and get started!**

# EvolveUI Documentation

## Navigation
- [Core](./evolve-core-documentation)
- [API](./evolve-api-documentation)
- [UI](./evolve-UI-documentation)

## EvolveUI (Optional Free Extension)

### Quick Links
- [Getting Started](#getting-started)
- [Configuration](#configuration)
- [Usage Guide](#usage)

## Contents
1. [Getting Started](#getting-started)
    - [Installation](#installation)
    - [Requirements](#requirements)
2. [Configuration](#configuration)
    - [Basic Configuration](#basic-configuration)
    - [Model Setup](#model-setup)
    - [Routing](#routing)
3. [Usage Guide](#usage)
    - [CRUD Operations](#crud-operations)
    - [Handling Relationships](#relationships)
    - [Validation](#validation)
    - [Customization](#customization)

---

## Getting Started

### 1.1 Installation

Install Evolve UI via Composer:

```sh
composer require thinkneverland/evolve-ui
```

---

### 1.2 Requirements

- PHP `^7.4|^8.0`
- Laravel `^8.0|^9.0`
- Livewire `^2.0`
- Evolve Core

## Configuration

### 2.1 Basic Configuration

Publish the configuration file:

```sh
php artisan vendor:publish --tag=evolve-ui-config
```

Configure in `config/evolve-ui.php`:

```php
return [
    'prefix' => env('EVOLVE_UI_PREFIX', ''),
    'middleware' => ['web', 'auth'],
    'per_page' => 10,
    'views' => [
        'layout' => 'layouts.app',
    ],
];
```

---

### 2.2 Model Setup

Implement the `EvolveModelInterface` in your models:

```php
use Thinkneverland\Evolve\Core\Contracts\EvolveModelInterface;
use Thinkneverland\Evolve\Core\Traits\EvolveModel;

class User extends Model implements EvolveModelInterface
{
    use EvolveModel;

    public static function shouldEvolve(): bool
    {
        return true;
    }

    public static function excludedFields(): array
    {
        return ['password'];
    }

    public static function excludedRelations(): array
    {
        return [];
    }
}
```

---

### 2.3 Routing

Routes are automatically registered for your Evolve models:

- **Index**: `/evolve/{model-plural}`
- **Create**: `/evolve/{model-plural}/create`
- **Edit**: `/evolve/{model-plural}/{id}/edit`
- **Show**: `/evolve/{model-plural}/{id}`

## Usage Guide

### 3.1 CRUD Operations

Evolve UI provides full CRUD functionality with:

- Searchable & sortable index views.
- Automatic form generation.
- Nested relationship handling.
- Delete confirmations.
- Transaction support.

---

### 3.2 Handling Relationships

Define relationships in your model:

```php
public static function getAllRelations(): array
{
    return [
        'customer' => ['type' => 'belongsTo'],
        'items' => ['type' => 'hasMany']
    ];
}
```

---

### 3.3 Validation

Implement validation rules:

```php
public static function getValidationRules(string $action, $model = null): array
{
    return [
        'fields.name' => 'required|string|max:255',
        'fields.email' => 'required|email|unique:users,email'
    ];
}
```

---

### 3.4 Customization

Publish view files for customization:

```sh
php artisan vendor:publish --tag=evolve-ui-views
```

Available views:

- `index.blade.php`: List view template.
- `create.blade.php`: Creation form template.
- `edit.blade.php`: Edit form template.
- `show.blade.php`: Detail view template.
- `fields.blade.php`: Field rendering partial.
