# Upgrade Guide

## 0.7.3

- `AttributeBag::merge()` was removed.
- `AttributeBag::set()` and `AttributeBag::setMany()` remove an attribute when the resolved value is `null`.
- `AttributeBag::replace()` should be used when replacing the entire attribute bag.

### Attribute bag merge migration

Before:

```php
use UIAwesome\Html\Helper\AttributeBag;

$attributes = ['class' => 'btn', 'disabled' => true];

AttributeBag::merge(
    $attributes,
    [
        'id' => 'save-button',
        'disabled' => null,
    ],
);
```

After, for additive updates:

```php
use UIAwesome\Html\Helper\AttributeBag;

$attributes = ['class' => 'btn', 'disabled' => true];

AttributeBag::setMany(
    $attributes,
    [
        'id' => 'save-button',
        'disabled' => null,
    ],
);
```

After, for full replacement:

```php
use UIAwesome\Html\Helper\AttributeBag;

$attributes = ['class' => 'btn', 'disabled' => true];

AttributeBag::replace(
    $attributes,
    [
        'id' => 'save-button',
    ],
);
```

Use `setMany()` to update existing attributes and apply key normalization, closure resolution, boolean normalization, and
`null` removal. Use `replace()` when the previous bag must be discarded before applying new values.

Note: `AttributeBag::merge()` was a raw bulk operation. It did not normalize keys and did not treat `null` values as
removals. If you depended on raw/null-preserving behavior, keep that logic explicit instead of switching directly to
`setMany()`.

## 0.7.2

- `BaseAttributes::normalizeKey()` / `Attributes::normalizeKey()` was moved to `AttributeBag::normalizeKey()`.

### Attribute key normalization migration

Before:

```php
use UIAwesome\Html\Helper\Attributes;

$key = Attributes::normalizeKey('label', 'aria-');
```

After:

```php
use UIAwesome\Html\Helper\AttributeBag;

$key = AttributeBag::normalizeKey('label', 'aria-');
```

The `get()`, `remove()`, `set()`, and `setMany()` methods also accept an optional prefix argument.

## 0.7.1

### Behavioral changes

- Boolean values in `aria-*`, `data-*`, `data-ng-*`, `ng-*`, and `on*` attributes are serialized as explicit strings.

Before, boolean-prefixed values could render as boolean attributes or empty strings depending on the path used.
After this change, `true` renders as `"true"` and `false` renders as `"false"` for prefixed attributes.

Example:

```php
use UIAwesome\Html\Helper\Attributes;

echo Attributes::render(
    [
        'aria' => ['expanded' => false],
        'data' => ['active' => true],
    ],
);
```

Output:

```html
 data-active="true" aria-expanded="false"
```

## 0.7.0

### Breaking changes

- `AttributeBag::add()` was removed.
- `AttributeBag::set()` no longer accepts the `$boolToString` argument.
- Prefix expansion for `aria`, `data`, `data-ng`, `ng`, and `on` is handled by `Attributes::render()` and `Attributes::normalizeAttributes()`.

### Attribute bag setter migration

Before:

```php
use UIAwesome\Html\Helper\AttributeBag;

$attributes = [];

AttributeBag::add($attributes, 'id', 'submit-button');
AttributeBag::set($attributes, 'expanded', false, 'aria-', true);
```

After:

```php
use UIAwesome\Html\Helper\AttributeBag;

$attributes = [];

AttributeBag::set($attributes, 'id', 'submit-button');
AttributeBag::set($attributes, 'expanded', false, 'aria-');
```

For grouped prefixed rendering, pass nested attributes to `Attributes::render()`:

```php
use UIAwesome\Html\Helper\Attributes;

echo Attributes::render(
    [
        'aria' => ['expanded' => false],
        'data' => ['controller' => 'menu'],
        'on' => ['click' => 'openMenu()'],
    ],
);
```

## 0.4.0

### Runtime dependencies

- `ui-awesome/html-interop` is no longer required at runtime by `ui-awesome/html-helper`.

If your application uses enums or interfaces from `ui-awesome/html-interop`, require that package directly in your
project.

```bash
composer require ui-awesome/html-interop
```

## 0.2.0

- `HTMLBuilder` and `Base\AbstractHTMLBuilder` were moved out of `ui-awesome/html-helper`.

Use the HTML builder APIs from `ui-awesome/html-core` instead.

Before:

```php
use UIAwesome\Html\Helper\HTMLBuilder;
```

After:

```bash
composer require ui-awesome/html-core
```
