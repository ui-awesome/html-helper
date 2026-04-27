<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Provider;

use PHPForge\Support\Stub\BackedInteger;
use Stringable;
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
     * @return array<string, array{mixed[], string, string|null, string}>
     */
    public static function get(): array
    {
        return [
            'default when missing' => [
                ['id' => 'submit'],
                'title',
                'fallback',
                'fallback',
            ],
            'existing value' => [
                ['role' => 'button'],
                'role',
                null,
                'button',
            ],
        ];
    }

    /**
     * @return array<string, array{mixed[], string|UnitEnum, string, mixed, mixed}>
     */
    public static function getWithPrefix(): array
    {
        return [
            'aria value by unprefixed key' => [
                ['aria-expanded' => 'true'],
                'expanded',
                'aria-',
                'fallback',
                'true',
            ],
            'data value by unprefixed key' => [
                ['data-toggle' => 'dropdown'],
                'toggle',
                'data-',
                'fallback',
                'dropdown',
            ],
            'data-ng value by unprefixed key' => [
                ['data-ng-model' => 'profile.email'],
                'model',
                'data-ng-',
                'fallback',
                'profile.email',
            ],
            'default when prefixed key is missing' => [
                ['data-target' => '#modal'],
                'toggle',
                'data-',
                'fallback',
                'fallback',
            ],
            'event value by unprefixed key' => [
                ['onclick' => 'handleClick()'],
                'click',
                'on',
                'fallback',
                'handleClick()',
            ],
            'enum key with prefix' => [
                ['aria-label' => 'Close'],
                Key::ARIA_LABEL,
                'aria-',
                'fallback',
                'Close',
            ],
            'ng value by unprefixed key' => [
                ['ng-if' => 'isVisible'],
                'if',
                'ng-',
                'fallback',
                'isVisible',
            ],
        ];
    }

    /**
     * @return array<string, array<string|UnitEnum>>
     */
    public static function invalidKey(): array
    {
        return [
            'empty string' => [''],
            'int backed enum' => [BackedInteger::VALUE],
        ];
    }

    /**
     * @return array<string, array{mixed[], string}>
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
     * @return array<string, array{string|Stringable|UnitEnum, string, string}>
     */
    public static function key(): array
    {
        return [
            'already prefixed data-ng' => [
                'data-ng-model',
                'data-ng-',
                'data-ng-model',
            ],
            'already prefixed ng' => [
                'ng-if',
                'ng-',
                'ng-if',
            ],
            'already prefixed on' => [
                'onsubmit',
                'on',
                'onsubmit',
            ],
            'enum with prefix aria' => [
                Key::ARIA_LABEL,
                'aria-',
                'aria-label',
            ],
            'enum with prefix data' => [
                Key::DATA_TOGGLE,
                'data-',
                'data-toggle',
            ],
            'enum with prefix event' => [
                Key::ON_CLICK,
                'on',
                'onclick',
            ],
            'string key with prefix aria' => [
                'aria-label',
                'aria-',
                'aria-label',
            ],
            'string key with prefix data' => [
                'data-toggle',
                'data-',
                'data-toggle',
            ],
            'string key with prefix event' => [
                'onclick',
                'on',
                'onclick',
            ],
            'stringable with prefix aria' => [
                new class {
                    public function __toString(): string
                    {
                        return 'aria-label';
                    }
                },
                'aria-',
                'aria-label',
            ],
            'stringable with prefix data' => [
                new class {
                    public function __toString(): string
                    {
                        return 'data-toggle';
                    }
                },
                'data-',
                'data-toggle',
            ],
            'stringable with prefix event' => [
                new class {
                    public function __toString(): string
                    {
                        return 'onclick';
                    }
                },
                'on',
                'onclick',
            ],
            'without prefix aria' => [
                'label',
                'aria-',
                'aria-label',
            ],
            'without prefix data' => [
                'toggle',
                'data-',
                'data-toggle',
            ],
            'without prefix data-ng' => [
                'model',
                'data-ng-',
                'data-ng-model',
            ],
            'without prefix ng' => [
                'if',
                'ng-',
                'ng-if',
            ],
            'without prefix event' => [
                'click',
                'on',
                'onclick',
            ],
        ];
    }


    /**
     * @return array<string, array{string|UnitEnum, string}>
     */
    public static function normalizeInvalidKey(): array
    {
        return [
            'empty string' => [
                '',
                '',
            ],
            'int backed enum' => [
                BackedInteger::VALUE,
                '',
            ],
        ];
    }

    /**
     * @return array<string, array{mixed[], string|UnitEnum, string}>
     */
    public static function remove(): array
    {
        return [
            'removes existing key' => [
                [
                    'id' => 'submit',
                    'role' => 'button',
                ],
                'role',
                ' id="submit"',
            ],
        ];
    }

    /**
     * @return array<string, array{mixed[], string|UnitEnum, string, string}>
     */
    public static function removeWithPrefix(): array
    {
        return [
            'aria key with prefix' => [
                ['id' => 'submit', 'aria-expanded' => 'true'],
                'expanded',
                'aria-',
                ' id="submit"',
            ],
            'data key with prefix' => [
                ['id' => 'submit', 'data-toggle' => 'dropdown'],
                'toggle',
                'data-',
                ' id="submit"',
            ],
            'data-ng key with prefix' => [
                ['id' => 'submit', 'data-ng-model' => 'email'],
                'model',
                'data-ng-',
                ' id="submit"',
            ],
            'event key with prefix' => [
                ['id' => 'submit', 'onclick' => 'handleClick()'],
                'click',
                'on',
                ' id="submit"',
            ],
            'enum key with prefix' => [
                ['id' => 'submit', 'data-toggle' => 'dropdown'],
                Key::DATA_TOGGLE,
                'data-',
                ' id="submit"',
            ],
            'ng key with prefix' => [
                ['id' => 'submit', 'ng-if' => 'isVisible'],
                'if',
                'ng-',
                ' id="submit"',
            ],
        ];
    }

    /**
     * @return array<string, array{mixed[], mixed[], string, string}>
     */
    public static function replace(): array
    {
        return [
            'replaces existing attributes' => [
                [
                    'class' => 'btn',
                    'id' => 'submit',
                ],
                [
                    'title' => 'Submit',
                ],
                '',
                ' title="Submit"',
            ],
            'replaces with prefixed attributes' => [
                [
                    'aria-hidden' => 'true',
                    'id' => 'dialog',
                ],
                [
                    'label' => 'Dialog',
                    'hidden' => null,
                ],
                'aria-',
                ' aria-label="Dialog"',
            ],
        ];
    }

    /**
     * @return array<
     *   string,
     *   array{mixed[], string|UnitEnum, bool|float|int|string|\Closure(): mixed|\Stringable|UnitEnum|null, string},
     * >
     */
    public static function set(): array
    {
        $closure = static fn(): string => 'submit';

        return [
            'aria attribute with boolean false value' => [
                [],
                'aria-expanded',
                false,
                ' aria-expanded="false"',
            ],
            'aria attribute with boolean true value' => [
                [],
                'aria-pressed',
                true,
                ' aria-pressed="true"',
            ],
            'closure with boolean false' => [
                [],
                'aria-expanded',
                static fn(): bool => false,
                ' aria-expanded="false"',
            ],
            'closure with boolean true' => [
                [],
                'aria-pressed',
                static fn(): bool => true,
                ' aria-pressed="true"',
            ],
            'data attribute with boolean false value' => [
                [],
                'data-active',
                false,
                ' data-active="false"',
            ],
            'data attribute with boolean true value' => [
                [],
                'data-active',
                true,
                ' data-active="true"',
            ],
            'data-ng attribute with boolean false value' => [
                [],
                'data-ng-required',
                false,
                ' data-ng-required="false"',
            ],
            'data-ng attribute with boolean true value' => [
                [],
                'data-ng-required',
                true,
                ' data-ng-required="true"',
            ],
            'does not stringify boolean for non-prefixed key containing aria substring' => [
                [],
                'foo-aria-pressed',
                true,
                ' foo-aria-pressed',
            ],
            'event attribute with boolean false value' => [
                [],
                'onclick',
                false,
                ' onclick="false"',
            ],
            'event attribute with boolean true value' => [
                [],
                'onclick',
                true,
                ' onclick="true"',
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
            'ng attribute with boolean false value' => [
                [],
                'ng-required',
                false,
                ' ng-required="false"',
            ],
            'ng attribute with boolean true value' => [
                [],
                'ng-required',
                true,
                ' ng-required="true"',
            ],
            'plain attribute value' => [
                [],
                'role',
                'button',
                ' role="button"',
            ],
            'removes key when value is null' => [
                ['data-toggle' => 'modal', 'id' => 'trigger'],
                Key::DATA_TOGGLE,
                null,
                ' id="trigger"',
            ],
        ];
    }

    /**
     * @return array<string, array{mixed[], mixed[], string}>
     */
    public static function setMany(): array
    {
        $closure = static fn(): string => 'submit';

        return [
            'plain attributes and removes null values' => [
                ['id' => 'button'],
                [
                    'id' => $closure,
                    'title' => 'Send form',
                    'disabled' => null,
                ],
                ' id="submit" title="Send form"',
            ],
            'prefixed keys from traits' => [
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

    /**
     * @return array<string, array{mixed[], mixed[], string, string}>
     */
    public static function setManyWithPrefix(): array
    {
        return [
            'aria values with bool stringification and null removal' => [
                ['aria-hidden' => 'true'],
                [
                    'label' => 'Dialog',
                    'expanded' => true,
                    'hidden' => null,
                    'controls' => static fn(): string => 'panel-1',
                ],
                'aria-',
                ' aria-label="Dialog" aria-expanded="true" aria-controls="panel-1"',
            ],
            'data values with bool stringification and null removal' => [
                ['data-disabled' => 'false'],
                [
                    'toggle' => 'dropdown',
                    'active' => false,
                    'disabled' => null,
                    'count' => static fn(): int => 3,
                ],
                'data-',
                ' data-toggle="dropdown" data-active="false" data-count="3"',
            ],
            'data-ng values with bool stringification and null removal' => [
                ['data-ng-disabled' => 'true'],
                [
                    'model' => 'profile.email',
                    'required' => true,
                    'disabled' => null,
                    'order' => static fn(): int => 1,
                ],
                'data-ng-',
                ' data-ng-model="profile.email" data-ng-required="true" data-ng-order="1"',
            ],
            'event values with bool stringification and null removal' => [
                ['onclick' => 'old()'],
                [
                    'change' => 'handleChange()',
                    'submit' => true,
                    'click' => null,
                    'focus' => static fn(): string => 'handleFocus()',
                ],
                'on',
                ' onchange="handleChange()" onsubmit="true" onfocus="handleFocus()"',
            ],
            'ng values with bool stringification and null removal' => [
                ['ng-hide' => '1'],
                [
                    'if' => 'isVisible',
                    'disabled' => false,
                    'hide' => null,
                    'repeat' => static fn(): string => 'item in items',
                ],
                'ng-',
                ' ng-if="isVisible" ng-disabled="false" ng-repeat="item in items"',
            ],
        ];
    }

    /**
     * @return array<
     *   string,
     *   array{mixed[], string|UnitEnum, bool|float|int|string|\Closure(): mixed|\Stringable|UnitEnum|null, string, string},
     * >
     */
    public static function setWithPrefix(): array
    {
        return [
            'aria boolean true when key is unprefixed' => [
                [],
                'expanded',
                true,
                'aria-',
                ' aria-expanded="true"',
            ],
            'aria scalar value with key normalization' => [
                [],
                'label',
                'Dialog',
                'aria-',
                ' aria-label="Dialog"',
            ],
            'data boolean false when key is unprefixed' => [
                [],
                'toggle',
                false,
                'data-',
                ' data-toggle="false"',
            ],
            'data scalar value with key normalization' => [
                [],
                'count',
                2,
                'data-',
                ' data-count="2"',
            ],
            'data-ng boolean true when key is unprefixed' => [
                [],
                'model',
                true,
                'data-ng-',
                ' data-ng-model="true"',
            ],
            'data-ng scalar value with key normalization' => [
                [],
                'repeat',
                'item in items',
                'data-ng-',
                ' data-ng-repeat="item in items"',
            ],
            'event boolean true when key is unprefixed' => [
                [],
                'click',
                true,
                'on',
                ' onclick="true"',
            ],
            'event scalar value with key normalization' => [
                [],
                'change',
                'handleChange()',
                'on',
                ' onchange="handleChange()"',
            ],
            'enum key with prefix' => [
                [],
                Key::ON_CLICK,
                'handleClick()',
                'on',
                ' onclick="handleClick()"',
            ],
            'ng boolean false when key is unprefixed' => [
                [],
                'if',
                false,
                'ng-',
                ' ng-if="false"',
            ],
            'ng scalar value with key normalization' => [
                [],
                'bind',
                1,
                'ng-',
                ' ng-bind="1"',
            ],
            'removes prefixed key when value is null' => [
                ['aria-label' => 'Close'],
                'label',
                null,
                'aria-',
                '',
            ],
        ];
    }
}
