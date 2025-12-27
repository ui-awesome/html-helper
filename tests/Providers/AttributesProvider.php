<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

use Stringable;
use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\{ButtonSize, Columns, Key, Priority, Theme};
use UnitEnum;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\AttributesTest} class.
 *
 * Supplies focused datasets used by attribute rendering helpers to build safe and predictable HTML attribute strings.
 *
 * The cases cover attribute ordering rules, handling of empty and `null`, enum normalization across common attribute
 * contexts, sanitization/encoding of potentially malicious inputs, and normalization of prefixed attribute keys.
 *
 * Key features.
 * - Cover enum in `class`, `data`, and `style` contexts.
 * - Exercise closures, scalars, and nested attribute groups used in tag attribute rendering.
 * - Normalize and validate prefixed attribute keys (`aria-*`, `data-*`, `on-*`).
 * - Provide deterministic datasets for ordering and filtering attributes.
 * - Validate safe handling of invalid names and malicious attribute value.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class AttributesProvider
{
    /**
     * Provides datasets for attribute ordering.
     *
     * Each dataset returns the expected rendered attributes string and the input attributes array. These cases validate
     * deterministic ordering across common attributes and ensure stable output for test assertions.
     *
     * @return array Test data for attribute ordering.
     *
     * @phpstan-return array<string, array{string, mixed[]}>
     */
    public static function attributeOrdering(): array
    {
        return [
            'multiple attributes in order' => [
                ' class="class" id="id" name="name" height="height" data-tests="data-tests"',
                [
                    'id' => 'id',
                    'class' => 'class',
                    'data-tests' => 'data-tests',
                    'name' => 'name',
                    'height' => 'height',
                ],
            ],
        ];
    }

    /**
     * Provides datasets for empty and `null` attribute handling.
     *
     * Each dataset returns the expected rendered attributes string and an input attributes array. The cases cover empty
     * attribute names, empty string, empty `class` arrays, invalid attribute names, and `null` to ensure invalid or
     * non-renderable entries are omitted consistently.
     *
     * @return array Test data for empty and null attribute handling.
     *
     * @phpstan-return array<string, array{string, mixed[]}>
     */
    public static function emptyAndNullValues(): array
    {
        return [
            'empty attribute name' => [
                '',
                ['' => 'value'],
            ],
            'empty attribute value' => [
                '',
                ['empty' => ''],
            ],
            'empty class array' => [
                '',
                ['class' => []],
            ],
            'invalid attribute name' => [
                ' valid="ok"',
                [
                    'valid' => 'ok',
                    '123-invalid' => 'bad',
                ],
            ],
            'null attribute value' => [
                '',
                ['null' => null],
            ],
        ];
    }

    /**
     * Provides datasets for enum-backed attribute rendering.
     *
     * Each dataset returns the expected rendered attributes string and an input attributes array. These cases validate
     * normalization of enum when used as scalars or within structured attributes such as `class`, `data`, and `style`.
     *
     * @return array Test data for enum-backed attribute rendering.
     *
     * @phpstan-return array<string, array{string, mixed[]}>
     */
    public static function enumAttribute(): array
    {
        return [
            'enum in class array' => [
                ' class="btn md"',
                [
                    'class' => [
                        'btn',
                        ButtonSize::MEDIUM,
                    ],
                ],
            ],
            'enum in data attribute' => [
                ' data-theme="dark"',
                [
                    'data' => ['theme' => Theme::DARK],
                ],
            ],
            'enum in style' => [
                ' style=\'width: lg;\'',
                [
                    'style' => ['width' => ButtonSize::LARGE],
                ],
            ],
            'mixed values' => [
                ' class="sm primary" data-theme="light"',
                [
                    'class' => [
                        ButtonSize::SMALL,
                        'primary',
                    ],
                    'data' => ['theme' => Theme::LIGHT],
                ],
            ],
            'numeric enum' => [
                ' cols="2"',
                ['cols' => Columns::TWO],
            ],
            'single enum' => [
                ' type="sm"',
                ['type' => ButtonSize::SMALL],
            ],
        ];
    }

    /**
     * Provides datasets for invalid attribute keys.
     *
     * Each dataset returns an invalid attribute key and a corresponding prefix. These cases cover empty strings and
     * enum keys to ensure such invalid inputs are identified for validation.
     *
     * @return array Test data for invalid attribute keys.
     *
     * @phpstan-return array<string, array{string|UnitEnum, string}>
     */
    public static function invalidKey(): array
    {
        return [
            'empty string' => [
                '',
                'aria-',
            ],
            'enum' => [
                Priority::HIGH,
                'data-',
            ],
        ];
    }

    /**
     * Provides datasets for attribute keys with prefixes.
     *
     * Each dataset returns the input attribute key, the prefix, and the expected normalized attribute key string. These
     * cases validate correct handling of prefixed attribute keys such as `aria-*`, `data-*`, and `on-*`, ensuring enums
     * and strings are normalized appropriately.
     *
     * @return array Test data for attribute keys with prefixes.
     *
     * @phpstan-return array<string, array{string|Stringable|UnitEnum, string, string}>
     */
    public static function key(): array
    {
        return [
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
            'without prefix event' => [
                'click',
                'on',
                'onclick',
            ],
        ];
    }

    /**
     * Provides datasets for malicious or unsafe attribute inputs.
     *
     * Each dataset returns the expected rendered attributes string and an input attributes array. The cases include
     * attribute containing HTML/JS payloads, unsafe nested in JSON-encoded data attributes, and invalid attribute keys
     * to ensure inputs are encoded or dropped deterministically.
     *
     * @return array Test data for malicious attribute handling.
     *
     * @phpstan-return array<string, array{string, mixed[]}>
     */
    public static function maliciousValues(): array
    {
        return [
            'malicious class value with XSS' => [
                ' class="safe &lt;svg/onload=alert()&gt;"',
                [
                    'class' => [
                        'safe',
                        '<svg/onload=alert()>',
                    ],
                ],
            ],
            'malicious data attribute key' => [
                '',
                [
                    'data' => ['key" onclick="alert(1)"' => 'value'],
                ],
            ],
            'nested array with script tag' => [
                ' data-key=\'{"sub":"\u0026lt;script\u0026gt;"}\'',
                [
                    'data' => [
                        'key' => ['sub' => '<script>'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Provides datasets for attribute normalization.
     *
     * Each dataset returns the input attributes array, the expected normalized attributes array, and an optional encode
     * flag (true/false) when relevant.
     *
     * @return array Test data for attribute normalization.
     *
     * @phpstan-return array<string, array{mixed[], mixed[], 2?: bool}>
     */
    public static function normalizeAttributes(): array
    {
        return [
            'boolean false (removed)' => [
                ['disabled' => false],
                [],
            ],
            'boolean true (preserved as bool)' => [
                ['checked' => true],
                ['checked' => true],
            ],
            'class array flattened' => [
                [
                    'class' => [
                        'btn',
                        'btn-primary',
                    ],
                ],
                ['class' => 'btn btn-primary'],
            ],
            'data attribute with unsupported types (boolean)' => [
                [
                    'data' => [
                        'active' => true,
                    ],
                ],
                [
                    'data-active' => '',
                ],
                true,
            ],
            'data expansion' => [
                [
                    'data' => [
                        'id' => 1,
                        'user' => 'admin',
                    ],
                ],
                [
                    'data-id' => '1',
                    'data-user' => 'admin',
                ],
            ],
            'encode false (Raw for SVG/DOM)' => [
                ['title' => '<script>'],
                ['title' => '<script>'],
                false,
            ],
            'encode true (HTML entities)' => [
                ['title' => '<script>'],
                ['title' => '&lt;script&gt;'],
                true,
            ],
            'enum' => [
                ['value' => ButtonSize::LARGE],
                ['value' => 'lg'],
            ],
            'enum inside class array' => [
                [
                    'class' => [
                        'btn',
                        ButtonSize::LARGE,
                    ],
                ],
                ['class' => 'btn lg'],
            ],
            'enum inside data array' => [
                ['data' => ['size' => ButtonSize::LARGE]],
                ['data-size' => 'lg'],
            ],
            'generic array attribute with encode false' => [
                ['my-attr' => ['<tag>']],
                ['my-attr' => '["<tag>"]'],
                false,
            ],
            'json flags in generic array with encode true' => [
                ['data' => ['tags' => ['<tag>']]],
                ['data-tags' => '["\u0026lt;tag\u0026gt;"]'],
                true,
            ],
            'json flags in style array with encode true' => [
                ['style' => ['--custom' => ['<val>']]],
                ['style' => '--custom: ["\u0026lt;val\u0026gt;"];'],
                true,
            ],
            'nested json with encode false' => [
                [
                    'data' => [
                        'config' => [
                            'key' => '<val>',
                        ],
                    ],
                ],
                ['data-config' => '{"key":"<val>"}'],
                false,
            ],
            'simple string' => [
                ['id' => 'my-id'],
                ['id' => 'my-id'],
            ],
            'stringable encoding with encode true' => [
                [
                    'data' => [
                        'val' => new class implements Stringable {
                            public function __toString(): string
                            {
                                return '<x>';
                            }
                        },
                    ],
                ],
                ['data-val' => '&lt;x&gt;'],
                true,
            ],
            'style array flattened' => [
                [
                    'style' => [
                        'color' => 'red',
                        'background' => 'blue',
                    ],
                ],
                ['style' => 'color: red; background: blue;'],
            ],
            'style property name encoding with encode true' => [
                ['style' => ['<prop>' => 'val']],
                ['style' => '&lt;prop&gt;: val;'],
                true,
            ],
        ];
    }

    /**
     * Provides datasets for rendering tag attributes.
     *
     * Each dataset returns the expected rendered attributes string and an input attributes array. The cases cover
     * boolean attributes (`true` and `false`), closures returning scalars and enums, numeric conversion, nested
     * attribute groups (for example, `data` and `aria`), and JSON encoding for array.
     *
     * @return array Test data for tag attribute rendering.
     *
     * @phpstan-return array<string, array{string, mixed[]}>
     */
    public static function renderTagAttributes(): array
    {
        return [
            'boolean' => [
                ' checked disabled required="yes"',
                [
                    'checked' => true,
                    'disabled' => true,
                    'hidden' => false,
                    'required' => 'yes',
                ],
            ],
            'class array' => [
                ' class="first second"',
                [
                    'class' => [
                        'first',
                        'second',
                    ],
                ],
            ],
            'closure with array' => [
                ' class="dynamic-class"',
                [
                    'class' => static fn(): array => ['dynamic-class'],
                ],
            ],
            'closure with boolean (false)' => [
                '',
                [
                    'disabled' => static fn(): bool => false,
                ],
            ],
            'closure with boolean (true)' => [
                ' disabled',
                [
                    'disabled' => static fn(): bool => true,
                ],
            ],
            'closure with empty string' => [
                '',
                [
                    'title' => static fn(): string => '',
                ],
            ],
            'closure with enum' => [
                ' type="sm"',
                [
                    'type' => static fn(): ButtonSize => ButtonSize::SMALL,
                ],
            ],
            'closure with float' => [
                ' data-value="0.42"',
                [
                    'data-value' => static fn(): float => 0.42,
                ],
            ],
            'closure with integer' => [
                ' tabindex="5"',
                [
                    'tabindex' => static fn(): int => 5,
                ],
            ],
            'closure with null' => [
                '',
                [
                    'readonly' => static fn(): bool|null => null,
                ],
            ],
            'closure with string' => [
                ' data-value="dynamic"',
                [
                    'data-value' => static fn(): string => 'dynamic',
                ],
            ],
            'data with array and scalar' => [
                ' data-a="0" data-b=\'[1,2]\' data-d="99.99" any="42"',
                [
                    'class' => [],
                    'style' => [],
                    'data' => [
                        'a' => 0,
                        'b' => [
                            1,
                            2,
                        ],
                        'c' => null,
                        'd' => 99.99,
                    ],
                    'any' => 42,
                ],
            ],
            'data with empty array' => [
                ' data-foo=\'[]\'',
                ['data' => ['foo' => []]],
            ],
            'float' => [
                ' width="99.99"',
                ['width' => 99.99],
            ],
            'integer' => [
                ' height="100"',
                ['height' => 100],
            ],
            'mixed with arrays' => [
                ' class="a b" id="x" data-a="1" data-b="2" style=\'width: 100px;\' any=\'[1,2]\'',
                [
                    'id' => 'x',
                    'class' => [
                        'a',
                        'b',
                    ],
                    'data' => [
                        'a' => 1,
                        'b' => 2,
                    ],
                    'style' => ['width' => '100px'],
                    'any' => [
                        1,
                        2,
                    ],
                ],
            ],
            'numeric and string' => [
                ' name="position" value="42"',
                [
                    'value' => 42,
                    'name' => 'position',
                ],
            ],
            'src and aria' => [
                ' src="xyz" aria-a="1" aria-b="c"',
                [
                    'src' => 'xyz',
                    'aria' => [
                        'a' => 1,
                        'b' => 'c',
                    ],
                ],
            ],
            'src and data' => [
                ' src="xyz" data-a="1" data-b="c"',
                [
                    'src' => 'xyz',
                    'data' => [
                        'a' => 1,
                        'b' => 'c',
                    ],
                ],
            ],
            'src and data-ng' => [
                ' src="xyz" data-ng-a="1" data-ng-b="c"',
                [
                    'src' => 'xyz',
                    'data-ng' => [
                        'a' => 1,
                        'b' => 'c',
                    ],
                ],
            ],
            'src and ng' => [
                ' src="xyz" ng-a="1" ng-b="c"',
                [
                    'src' => 'xyz',
                    'ng' => [
                        'a' => 1,
                        'b' => 'c',
                    ],
                ],
            ],
            'stringable attribute' => [
                ' alt="an image"',
                ['alt' => new class {
                    public function __toString(): string
                    {
                        return 'an image';
                    }
                },
                ],
            ],
        ];
    }

    /**
     * Provides datasets for `style` attribute rendering.
     *
     * Each dataset returns the expected rendered attributes string and an input attributes array. The cases cover
     * scalar style, stringable, list and nested arrays encoded into inline style, boolean and numeric conversions, and
     * omission of `null` style entries.
     *
     * @return array Test data for style attribute rendering.
     *
     * @phpstan-return array<string, array{string, mixed[]}>
     */
    public static function styleAttributes(): array
    {
        return [
            'style array with scalar values' => [
                ' style=\'width: 100px; height: 200px;\'',
                [
                    'style' => [
                        'width' => '100px',
                        'height' => '200px',
                    ],
                ],
            ],
            'style with array value' => [
                ' style=\'complex-property: ["value1","value2"];\'',
                [
                    'style' => [
                        'complex-property' => [
                            'value1',
                            'value2',
                        ],
                    ],
                ],
            ],
            'style with boolean value' => [
                ' style=\'flag: true;\'',
                [
                    'style' => ['flag' => true],
                ],
            ],
            'style with float value' => [
                ' style=\'opacity: 0.5; font-size: 1.5;\'',
                [
                    'style' => [
                        'opacity' => 0.5,
                        'font-size' => 1.5,
                    ],
                ],
            ],
            'style with nested array value' => [
                ' style=\'config: {"nested":{"key":"value"}};\'',
                [
                    'style' => [
                        'config' => [
                            'nested' => ['key' => 'value'],
                        ],
                    ],
                ],
            ],
            'style with null value' => [
                '',
                [
                    'style' => ['nullable' => null],
                ],
            ],
            'style with special characters' => [
                ' style=\'font-family: Times &amp; Serif;\'',
                [
                    'style' => ['font-family' => 'Times & Serif'],
                ],
            ],
            'style with stringable value' => [
                ' style=\'content: stringable value;\'',
                [
                    'style' => [
                        'content' => new class {
                            public function __toString(): string
                            {
                                return 'stringable value';
                            }
                        },
                    ],
                ],
            ],
        ];
    }
}
