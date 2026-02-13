<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support\Provider;

use Closure;
use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\{Key, Priority};
use UnitEnum;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\AttributeBagTest} test cases.
 *
 * Provides representative input/output pairs for attribute bag operations.
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class AttributeBagProvider
{
    /**
     * @phpstan-return array<string, array{mixed[], string|UnitEnum, string|null, mixed[]}>
     */
    public static function add(): array
    {
        return [
            'accepts UnitEnum key' => [
                ['id' => 'btn-save'],
                Key::ARIA_LABEL,
                'Save button',
                ['id' => 'btn-save', 'aria-label' => 'Save button'],
            ],
            'sets value by string key' => [
                ['class' => 'btn'],
                'role',
                'button',
                ['class' => 'btn', 'role' => 'button'],
            ],
            'unsets key on null value' => [
                ['data-toggle' => 'modal', 'id' => 'dialog'],
                'data-toggle',
                null,
                ['id' => 'dialog'],
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{mixed[], string, string|null, string}>
     */
    public static function get(): array
    {
        return [
            'returns default when missing' => [
                ['id' => 'submit'],
                'title',
                'fallback',
                'fallback',
            ],
            'returns existing value' => [
                ['role' => 'button'],
                'role',
                null,
                'button',
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{string|UnitEnum}>
     */
    public static function invalidKey(): array
    {
        return [
            'empty string' => [''],
            'int backed enum' => [Priority::HIGH],
        ];
    }

    /**
     * @phpstan-return array<string, array{mixed[], mixed[], mixed[]}>
     */
    public static function merge(): array
    {
        return [
            'merges and overrides existing keys' => [
                ['class' => 'btn', 'id' => 'submit'],
                ['class' => 'btn btn-primary', 'title' => 'Submit'],
                ['class' => 'btn btn-primary', 'id' => 'submit', 'title' => 'Submit'],
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{mixed[], string|UnitEnum, mixed[]}>
     */
    public static function remove(): array
    {
        return [
            'removes existing key' => [
                ['id' => 'submit', 'role' => 'button'],
                'role',
                ['id' => 'submit'],
            ],
        ];
    }

    /**
     * @phpstan-return array<
     *   string,
     *   array{
     *     mixed[],
     *     mixed,
     *     bool|float|int|string|Closure(): mixed|\Stringable|UnitEnum|null,
     *     string,
     *     bool,
     *     mixed[],
     *   },
     * >
     */
    public static function set(): array
    {
        return [
            'boolean to string conversion disabled by default' => [
                [],
                'disabled',
                true,
                '',
                false,
                ['disabled' => true],
            ],
            'boolean to string conversion enabled' => [
                [],
                'hidden',
                false,
                'aria-',
                true,
                ['aria-hidden' => 'false'],
            ],
            'resolves closure value' => [
                [],
                'label',
                static fn(): string => 'Dynamic label',
                'aria-',
                false,
                ['aria-label' => 'Dynamic label'],
            ],
            'supports aria prefix with enum key' => [
                [],
                Key::ARIA_LABEL,
                'Dialog title',
                'aria-',
                false,
                ['aria-label' => 'Dialog title'],
            ],
            'supports data prefix with enum key' => [
                [],
                Key::DATA_TOGGLE,
                'dropdown',
                'data-',
                false,
                ['data-toggle' => 'dropdown'],
            ],
            'supports on prefix with enum key' => [
                [],
                Key::ON_CLICK,
                'handleClick()',
                'on',
                false,
                ['onclick' => 'handleClick()'],
            ],
            'removes key when resolved value is null' => [
                ['data-toggle' => 'modal', 'id' => 'trigger'],
                Key::DATA_TOGGLE,
                static fn() => null,
                'data-',
                false,
                ['id' => 'trigger'],
            ],
        ];
    }
}
