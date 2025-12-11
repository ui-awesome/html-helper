<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\{ButtonSize, Columns, Theme};

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\AttributesTest} class.
 *
 * Supplies comprehensive test data for validating the handling, propagation, and override of HTML attributes according
 * to the HTML specification, including assignment, ordering, empty and `null` value handling, enum integration, and
 * security against malicious input.
 *
 * The test data covers real-world scenarios for appending, overriding, and removing attributes, supporting both
 * explicit `string` values and `null` for attribute removal, to ensure consistent output across different rendering
 * configurations.
 *
 * The provider organizes test cases with descriptive names for precise identification of failure cases during test
 * execution and debugging.
 *
 * Key features.
 * - Ensures correct propagation, appending, and override of HTML attributes in element rendering.
 * - Named test data sets for accurate failure identification.
 * - Security-focused cases for XSS and invalid input handling.
 * - Validation of empty `string`, `null`, enum, and standard string values for attributes.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class AttributesProvider
{
    /**
     * Provides test cases for attribute ordering scenarios.
     *
     * Supplies test data for validating the consistent ordering of HTML attributes when rendered.
     *
     * Each test case includes the input attributes and the expected rendered output.
     *
     * @return array Test data for attribute ordering scenarios.
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
     * Provides test cases for empty and `null` value handling.
     *
     * Supplies test data for validating how the attribute renderer handles empty strings, `null` values, empty arrays,
     * and invalid attribute names.
     *
     * Each test case includes the input attributes and the expected rendered output.
     *
     * @return array Test data for empty/`null` scenarios.
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
     * Provides test cases for enum attribute scenarios.
     *
     * Supplies test data for validating HTML attribute rendering with PHP enum values, including integration in
     * `class`, `data`, `style`, and numeric attributes.
     *
     * Each test case includes the input attributes and the expected rendered output.
     *
     * @return array Test data for enum attribute scenarios.
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
                ' style="width: lg;"',
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
            'nested enum' => [
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
     * Provides test cases for malicious value handling and XSS prevention.
     *
     * Supplies test data for validating security measures in HTML attribute rendering including XSS attack prevention,
     * script injection blocking, and special character encoding.
     *
     * Each test case includes the input attributes and the expected rendered output.
     *
     * @return array Test data for security scenarios.
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
     * Provides test cases for HTML attribute rendering scenarios.
     *
     * Supplies comprehensive test data for validating HTML attribute expansion, boolean and enum handling, and edge
     * case processing.
     *
     * Each test case includes the input attributes and the expected rendered output.
     *
     * @return array Test data for attribute rendering scenarios.
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
                ' class="a b" id="x" data-a="1" data-b="2" style="width: 100px;" any=\'[1,2]\'',
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
        ];
    }

    /**
     * Provides test cases for style attribute rendering scenarios.
     *
     * Supplies test data for validating style attribute handling with various value types including arrays, booleans,
     * nested structures, `null` values, and special characters.
     *
     * Each test case includes the input attributes and the expected rendered output.
     *
     * @return array Test data for style attribute scenarios.
     *
     * @phpstan-return array<string, array{string, mixed[]}>
     */
    public static function styleAttributes(): array
    {
        return [
            'style array with scalar values' => [
                ' style="width: 100px; height: 200px;"',
                [
                    'style' => [
                        'width' => '100px',
                        'height' => '200px',
                    ],
                ],
            ],
            'style with array value' => [
                ' style="complex-property: ["value1","value2"];"',
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
                ' style="flag: true;"',
                [
                    'style' => ['flag' => true],
                ],
            ],
            'style with float value' => [
                ' style="opacity: 0.5; font-size: 1.5;"',
                [
                    'style' => [
                        'opacity' => 0.5,
                        'font-size' => 1.5,
                    ],
                ],
            ],
            'style with nested array value' => [
                ' style="config: {"nested":{"key":"value"}};"',
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
                ' style="font-family: Times &amp; Serif;"',
                [
                    'style' => ['font-family' => 'Times & Serif'],
                ],
            ],
        ];
    }
}
