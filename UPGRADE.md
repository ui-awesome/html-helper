# Upgrade Guide

## 0.7.3

### Attribute bag updates

`AttributeBag::merge()` was removed. Use `setMany()` for additive updates or `replace()` when the previous bag must be
discarded.

```php
$attributes = ['class' => 'btn', 'disabled' => true];

AttributeBag::setMany(
    $attributes,
    [
        'id' => 'save-button',
        'disabled' => null,
    ],
);
```

`set()` and `setMany()` now remove an attribute when its resolved value is `null`. Code that relied on raw or
null-preserving merge behavior must keep that behavior explicit.

## 0.7.2

### Attribute key normalization

`BaseAttributes::normalizeKey()` and `Attributes::normalizeKey()` moved to `AttributeBag::normalizeKey()`:

```php
// Before
$key = Attributes::normalizeKey('label', 'aria-');

// After
$key = AttributeBag::normalizeKey('label', 'aria-');
```

`get()`, `remove()`, `set()`, and `setMany()` also accept an optional prefix.

## 0.7.1

### Prefixed boolean values

Boolean values in `aria-*`, `data-*`, `data-ng-*`, `ng-*`, and `on*` attributes are serialized as explicit `"true"`
or `"false"` strings.

```php
echo Attributes::render(
    [
        'aria' => ['expanded' => false],
        'data' => ['active' => true],
    ],
);
```

## 0.7.0

### Attribute bag setters

Replace `AttributeBag::add()` with `AttributeBag::set()`. The `$boolToString` argument was removed from `set()`:

```php
// Before
AttributeBag::add($attributes, 'id', 'submit-button');
AttributeBag::set($attributes, 'expanded', false, 'aria-', true);

// After
AttributeBag::set($attributes, 'id', 'submit-button');
AttributeBag::set($attributes, 'expanded', false, 'aria-');
```

Prefix expansion for `aria`, `data`, `data-ng`, `ng`, and `on` is handled by `Attributes::render()` and
`Attributes::normalizeAttributes()`.

## 0.2.0

`HTMLBuilder` and `Base\AbstractHTMLBuilder` moved to the core package. Replace helper builder imports with
`UIAwesome\Html\Core\Html`.
