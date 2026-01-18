# Usage examples

This document provides practical, framework-agnostic examples of how to use the helpers provided by this package.

All helpers are static and designed to be used in view rendering, template generation, and HTML/SVG workflows.

## Attributes

### Render a button with data and boolean attributes

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Attributes;

$attributes = [
    'id' => 'submit-btn',
    'class' => ['btn', 'btn-primary'],
    'type' => 'submit',
    'disabled' => false,
    'data' => [
        'action' => 'save',
        'payload' => ['draft' => true, 'source' => 'toolbar'],
    ],
    'aria' => [
        'label' => 'Save changes',
    ],
];

echo '<button' . Attributes::render($attributes) . '>Save</button>';
// <button class="btn btn-primary" id="submit-btn" type="submit" data-action="save" data-payload='{"draft":true,"source":"toolbar"}' aria-label="Save changes">
// Save
// </button>
```

### Prepare raw attributes for DOMDocument (avoid double-escaping)

When you insert attributes via DOM APIs, the DOM engine will escape values itself. In that case, normalize attributes
with `encode: false`.

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Attributes;

$attributes = [
    'title' => '5 < 10',
    'data-config' => ['key' => '<raw>'],
    'required' => true,
];

$raw = Attributes::normalizeAttributes($attributes, encode: false);

foreach ($raw as $name => $value) {
    if ($value === true) {
        // DOM APIs expect strings; boolean attributes are represented by their presence.
        $domElement->setAttribute($name, $name);
        continue;
    }

    $domElement->setAttribute($name, $value);
}
```

### Normalize prefixed keys (aria/data/on)

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Attributes;

$ariaLabel = Attributes::normalizeKey('label', 'aria-');
// aria-label

$dataId = Attributes::normalizeKey('id', 'data-');
// data-id

$onClick = Attributes::normalizeKey('click', 'on');
// onclick
```

## CSSClass

### Merge classes into an attribute array

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\CSSClass;

$attributes = ['id' => 'main', 'class' => 'layout'];

CSSClass::add($attributes, ['layout--wide', 'is-active']);

// Override existing classes.
CSSClass::add($attributes, 'layout layout--compact', true);

// $attributes['class'] is now "layout layout--compact".
```

### Validate a variant against an allow-list

`CSSClass::render()` validates the variant and formats the final class using `sprintf()`.

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\CSSClass;

$variant = 'primary';

$buttonVariantClass = CSSClass::render($variant, 'btn-%s', ['primary', 'secondary', 'ghost']);
// btn-primary
```

## Encode

### Encode user input for HTML content

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Encode;

$userInput = '<script>alert("xss")</script>';

echo '<p>' . Encode::content($userInput) . '</p>';
// <p>&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;</p>
```

### Encode a value for a quoted HTML attribute

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Encode;

$value = 'O\'Reilly & <tag>';

echo '<input value="' . Encode::value($value) . '">';
// <input value="O&apos;Reilly &amp; &lt;tag&gt;">
```

### Control double-encoding for already-encoded values

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Encode;

$alreadyEncoded = '&lt;b&gt;Hello&lt;/b&gt;';

echo Encode::content($alreadyEncoded, doubleEncode: false);
// &lt;b&gt;Hello&lt;/b&gt;
```

## Enum

### Normalize a single enum or string value

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Enum;

$value = Enum::normalizeValue('primary');
// primary
```

### Normalize a mixed array

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Enum;

$values = Enum::normalizeArray(['primary', 1, null, true]);
// ['primary', 1, null, true]
```

## Naming

### Build nested input names and deterministic IDs

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Naming;

$name = Naming::generateInputName('User', 'profile[0][email]');
// User[profile][0][email]

$id = Naming::generateInputId('User', 'profile[0][email]');
// user-profile-0-email
```

### Generate an arrayable name for multi-value inputs

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Naming;

$name = Naming::generateArrayableName('tags');
// tags[]
```

### Generate unique IDs for components

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Naming;

$id = Naming::generateId('toast-');
// toast-...
```

### Convert a regular expression literal for the HTML `pattern` attribute

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Naming;

$pattern = Naming::convertToPattern('/^[a-z]+$/i');
// ^[a-z]+$
```

### Extract short class names

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Naming;

$short = Naming::getShortNameClass('App\\Model\\User');
// User::class

$shortNoSuffix = Naming::getShortNameClass('App\\Model\\User', suffix: false);
// User
```

## Template

### Render a small input template

`Template::render()` does not encode token values. Encode before injecting values.

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Encode;
use UIAwesome\Html\Helper\Template;

$template = "<label for=\"{id}\">{label}</label>\n<input id=\"{id}\" name=\"{name}\" value=\"{value}\">";

$tokens = [
    '{id}' => 'user-email',
    '{name}' => 'User[email]',
    '{label}' => Encode::content('Email'),
    '{value}' => Encode::value('user@example.com'),
];

echo Template::render($template, $tokens);
```

## Validator

### Validate and format a variant value

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Validator;

$size = 'md';

Validator::oneOf($size, ['sm', 'md', 'lg'], 'size');
```

### Validate integer-like values for pagination

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Validator;

$page = $_GET['page'] ?? '1';

if (Validator::intLike($page, 1, 1000) === false) {
    throw new InvalidArgumentException('Invalid page.');
}

$pageNumber = (int) $page;
```

### Validate ratio/percent offsets for SVG gradients (`offsetLike()`)

`Validator::offsetLike()` accepts either ratio values (`0` to `1`) or percent strings (`0%` to `100%`).

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Attributes;
use UIAwesome\Html\Helper\Validator;

$offset = $_GET['offset'] ?? '50%';

if (Validator::offsetLike($offset) === false) {
    throw new InvalidArgumentException('Invalid gradient offset.');
}

echo '<stop' . Attributes::render([
    'offset' => (string) $offset,
    'stop-color' => '#ff6600',
]) . ' />';
// <stop offset="50%" stop-color="#ff6600" />
```

### Validate positive-like numeric values

```php
<?php

declare(strict_types=1);

use UIAwesome\Html\Helper\Validator;

$opacity = '0.75';

if (Validator::positiveLike($opacity, 0.0, 1.0) === false) {
    throw new InvalidArgumentException('Invalid opacity.');
}
```
