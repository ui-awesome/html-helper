# Installation

HTML Helper is framework-agnostic and requires PHP 8.3 or newer and the `mbstring` extension.

```bash
composer require ui-awesome/html-helper:^0.7
```

Load the application's Composer autoloader and use the `UIAwesome\Html\Helper` namespace. No Yii framework,
frontend build, JavaScript assets, or service configuration is required.

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use UIAwesome\Html\Helper\Encode;

echo '<p>' . Encode::content('<Hello>') . '</p>';
// <p>&lt;Hello&gt;</p>
```

Consult [usage examples](examples.md), [testing](testing.md), and [AI assistance](agents.md) for the next steps.
