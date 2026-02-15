<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Provider;

use PHPForge\Support\Stub\BackedInteger;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Support\Key;
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
            'int backed enum' => [BackedInteger::VALUE],
        ];
    }

    /**
     * @phpstan-return array<string, array{mixed[], string}>
     */
    public static function invalidManyKey(): array
    {
        return [
            'empty string key in values array' => [
                ['' => 'value'],
                Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage(),
            ],
            'integer key in values array' => [
                [1 => 'value'],
                Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage(),
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{mixed[], mixed[], string}>
     */
    public static function merge(): array
    {
        return [
            'merges and overrides existing keys' => [
                ['class' => 'btn', 'id' => 'submit'],
                ['class' => 'btn btn-primary', 'title' => 'Submit'],
                ' class="btn btn-primary" id="submit" title="Submit"',
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{mixed[], string|UnitEnum, string}>
     */
    public static function remove(): array
    {
        return [
            'removes existing key' => [
                ['id' => 'submit', 'role' => 'button'],
                'role',
                ' id="submit"',
            ],
        ];
    }

    /**
     * @phpstan-return array<
     *   string,
     *   array{mixed[], string|UnitEnum, bool|float|int|string|\Closure(): mixed|\Stringable|UnitEnum|null, string},
     * >
     */
    public static function set(): array
    {
        $closure = static fn(): string => 'submit';

        return [
            'does not stringify boolean for non-prefixed key containing aria substring' => [
                [],
                'foo-aria-pressed',
                true,
                ' foo-aria-pressed',
            ],
            'keeps closure value as raw data' => [
                [],
                'id',
                $closure,
                ' id="submit"',
            ],
            'keeps enum value as raw data' => [
                [],
                'type',
                BackedInteger::VALUE,
                ' type="1"',
            ],
            'removes key when value is null' => [
                ['data-toggle' => 'modal', 'id' => 'trigger'],
                Key::DATA_TOGGLE,
                null,
                ' id="trigger"',
            ],
            'sets aria attribute with boolean true value' => [
                [],
                'aria-pressed',
                true,
                ' aria-pressed="true"',
            ],
            'sets aria attribute with boolean false value' => [
                [],
                'aria-expanded',
                false,
                ' aria-expanded="false"',
            ],
            'sets plain attribute value' => [
                [],
                'role',
                'button',
                ' role="button"',
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{mixed[], mixed[], string}>
     */
    public static function setMany(): array
    {
        $closure = static fn(): string => 'submit';

        return [
            'sets many plain attributes and removes null values' => [
                ['id' => 'button'],
                [
                    'id' => $closure,
                    'title' => 'Send form',
                    'disabled' => null,
                ],
                ' id="submit" title="Send form"',
            ],
            'supports prefixed keys from traits' => [
                [],
                [
                    'aria-label' => 'Close modal',
                    'data-toggle' => 'dropdown',
                    'onclick' => 'handleClick()',
                ],
                ' aria-label="Close modal" data-toggle="dropdown" onclick="handleClick()"',
            ],
        ];
    }
}
