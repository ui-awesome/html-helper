<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Provider;

use PHPForge\Support\Stub\{BackedInteger, BackedString};
use Stringable;
use UIAwesome\Html\Helper\Tests\Support\Key;
use UnitEnum;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\AttributesTest} test cases.
 *
 * Provides representative input/output pairs for rendering and normalizing HTML attributes.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class AttributesProvider
{
    /**
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
     * @phpstan-return array<string, array{string, mixed[]}>
     */
    public static function enumAttribute(): array
    {
        return [
            'enum in class array' => [
                ' class="btn value"',
                [
                    'class' => [
                        'btn',
                        BackedString::VALUE,
                    ],
                ],
            ],
            'enum in data attribute' => [
                ' data-theme="value"',
                [
                    'data' => ['theme' => BackedString::VALUE],
                ],
            ],
            'enum in style' => [
                ' style=\'width: value;\'',
                [
                    'style' => ['width' => BackedString::VALUE],
                ],
            ],
            'mixed values' => [
                ' class="value primary" data-theme="value"',
                [
                    'class' => [
                        BackedString::VALUE,
                        'primary',
                    ],
                    'data' => ['theme' => BackedString::VALUE],
                ],
            ],
            'numeric enum' => [
                ' cols="1"',
                ['cols' => BackedInteger::VALUE],
            ],
            'single enum' => [
                ' type="value"',
                ['type' => BackedString::VALUE],
            ],
        ];
    }

    /**
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
                BackedInteger::VALUE,
                'data-',
            ],
        ];
    }

    /**
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
            'data attribute with boolean true' => [
                [
                    'data' => [
                        'active' => true,
                    ],
                ],
                [
                    'data-active' => 'true',
                ],
                true,
            ],
            'data attribute with boolean false' => [
                [
                    'data' => [
                        'active' => false,
                    ],
                ],
                [
                    'data-active' => 'false',
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
            'data expansion with stringable value' => [
                [
                    'data' => [
                        'user' => new class {
                            public function __toString(): string
                            {
                                return 'admin';
                            }
                        },
                    ],
                ],
                [
                    'data-user' => 'admin',
                ],
            ],
            'data expansion with null value' => [
                [
                    'data' => [
                        'user' => null,
                    ],
                ],
                [],
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
                ['value' => BackedString::VALUE],
                ['value' => 'value'],
            ],
            'enum inside class array' => [
                [
                    'class' => [
                        'btn',
                        BackedString::VALUE,
                    ],
                ],
                ['class' => 'btn value'],
            ],
            'enum inside data array' => [
                ['data' => ['size' => BackedString::VALUE]],
                ['data-size' => 'value'],
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
            'on expansion array json encoding with encode false' => [
                [
                    'on' => [
                        'click' => ['payload' => '<tag>'],
                    ],
                ],
                ['onclick' => '{"payload":"<tag>"}'],
                false,
            ],
            'on expansion array json encoding with encode true' => [
                [
                    'on' => [
                        'click' => ['payload' => '<tag>'],
                    ],
                ],
                ['onclick' => '{"payload":"\u0026lt;tag\u0026gt;"}'],
                true,
            ],
            'on expansion keeps exact key on' => [
                ['on' => ['on' => 'handleOn()']],
                ['on' => 'handleOn()'],
            ],
            'on expansion preserves prefixed key' => [
                ['on' => ['onclick' => 'handleClick()']],
                ['onclick' => 'handleClick()'],
            ],
            'on expansion supports boolean true value' => [
                ['on' => ['change' => true]],
                ['onchange' => 'true'],
            ],
            'on expansion supports float value' => [
                ['on' => ['wheel' => 3.14]],
                ['onwheel' => '3.14'],
            ],
            'on expansion supports integer value' => [
                ['on' => ['keyup' => 13]],
                ['onkeyup' => '13'],
            ],
            'on expansion with short event key' => [
                ['on' => ['click' => 'handleClick()']],
                ['onclick' => 'handleClick()'],
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
                ' type="value"',
                [
                    'type' => static fn(): BackedString => BackedString::VALUE,
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
            'src and on' => [
                ' src="xyz" onclick="handleClick()" onsubmit="return false"',
                [
                    'src' => 'xyz',
                    'on' => [
                        'click' => 'handleClick()',
                        'onsubmit' => 'return false',
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
