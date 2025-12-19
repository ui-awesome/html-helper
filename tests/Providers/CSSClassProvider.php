<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

use Stringable;
use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\{AlertType, ButtonSize, Priority};
use UnitEnum;

use function array_map;
use function implode;
use function range;
use function str_repeat;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\CSSClassTest} class.
 *
 * Supplies focused datasets used by CSS class helpers to merge, normalize, and render `class` attribute.
 *
 * The cases cover scalar and enum inputs, multi-operation add semantics, duplicate elimination, invalid token
 * filtering, whitespace normalization, and override behaviour while preserving unrelated attributes.
 *
 * Key features.
 * - Cover edge cases including invalid tokens, large inputs, and mixed whitespace separators.
 * - Provide datasets for class rendering with allowed lists and formatted base strings.
 * - Return operation sequences describing incremental merges and override behaviour across arrays, strings, and enums.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class CSSClassProvider
{
    /**
     * Provides datasets for class rendering.
     *
     * Each dataset returns: class, base class format, allowed list, expected output, and an assertion
     * message. The cases validate enum normalization, string matching against allowed, and deterministic formatting of
     * rendered class.
     *
     * @phpstan-return array<string, array{string|UnitEnum, string, list<string|UnitEnum>, string, string}>
     */
    public static function renderValues(): array
    {
        return [
            'single enum' => [
                ButtonSize::LARGE,
                'btn-%s',
                ['sm', 'lg'],
                'btn-lg',
                'Should match Enum value against string whitelist and render.',
            ],
            'valid enum' => [
                AlertType::WARNING,
                'alert-%s',
                [AlertType::INFO, AlertType::WARNING],
                'alert-warning',
                'Should render formatted class string for valid Enum input.',
            ],
            'valid string' => [
                'info',
                'alert-%s',
                ['info', 'warning', 'danger'],
                'alert-info',
                'Should render formatted class string for valid string input.',
            ],
        ];
    }

    /**
     * Provides datasets for class attribute merging.
     *
     * Each dataset returns: initial attributes, an ordered list of operations, expected attributes, and an assertion
     * message. The cases validate merging semantics, token filtering, whitespace normalization, duplicate elimination,
     * override behaviour, and preservation of unrelated attributes.
     *
     * @phpstan-return array<
     *   string,
     *   array{
     *     mixed[],
     *     list<array{classes: mixed[]|string|Stringable|UnitEnum|null, override?: bool}>,
     *     mixed[],
     *     string,
     *   },
     * >
     */
    public static function values(): array
    {
        return [
            // array of strings
            'mixed valid and empty strings in array' => [
                [],
                [
                    [
                        'classes' => [
                            'class-one',
                            '',
                            'class-two',
                            null,
                            'class-three',
                        ],
                    ],
                ],
                ['class' => 'class-one class-two class-three'],
                "Should filter out empty strings and 'null' values from array.",
            ],
            'multiple classes from array' => [
                [],
                [
                    [
                        'classes' => [
                            'class-one',
                            'class-two',
                            'class-three',
                        ],
                    ],
                ],
                ['class' => 'class-one class-two class-three'],
                'Should add multiple classes from array.',
            ],
            'single class from array' => [
                [],
                [['classes' => ['class-one']]],
                ['class' => 'class-one'],
                'Should add single class from array.',
            ],

            // complex real-world scenario
            'complex Tailwind alert with enum' => [
                [
                    'id' => 'alert-box',
                    'role' => 'alert',
                ],
                [
                    [
                        'classes' => [
                            'p-4',
                            'mb-4',
                            'text-sm',
                            AlertType::WARNING,
                            'rounded-lg',
                            'border',
                            'border-yellow-300',
                            'bg-yellow-50',
                            'dark:bg-gray-800',
                            'dark:text-yellow-400',
                            'dark:border-yellow-800',
                        ],
                    ],
                ],
                [
                    'id' => 'alert-box',
                    'role' => 'alert',
                    'class' => 'p-4 mb-4 text-sm warning rounded-lg border border-yellow-300 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-400 dark:border-yellow-800',
                ],
                'Should handle complex real-world scenario with Tailwind style classes and enum.',
            ],

            // duplicate handling
            'array with duplicate classes' => [
                [],
                [
                    [
                        'classes' => [
                            'class-one',
                            'class-two',
                            'class-one',
                            'class-three',
                            'class-two',
                        ],
                    ],
                ],
                ['class' => 'class-one class-two class-three'],
                'Should remove duplicates within the same array input.',
            ],
            'duplicate class to existing class' => [
                ['class' => 'class-one'],
                [['classes' => 'class-one']],
                ['class' => 'class-one'],
                'Should not duplicate existing class when adding the same class.',
            ],
            'mixed new and duplicate classes' => [
                ['class' => 'class-one class-two'],
                [
                    [
                        'classes' => [
                            'class-two',
                            'class-three',
                            'class-one',
                            'class-four',
                        ],
                    ],
                ],
                ['class' => 'class-one class-two class-three class-four'],
                'Should append only new classes and ignore duplicates.',
            ],
            'multiple operations with appending' => [
                [],
                [
                    ['classes' => 'class-one'],
                    ['classes' => 'class-two'],
                    [
                        'classes' => [
                            'class-three',
                            'class-four',
                        ],
                    ],
                ],
                ['class' => 'class-one class-two class-three class-four'],
                'Should append classes from multiple operations.',
            ],
            'new class to existing class' => [
                ['class' => 'class-one'],
                [['classes' => 'class-two']],
                ['class' => 'class-one class-two'],
                'Should append new class to existing classes.',
            ],
            'string with duplicate classes' => [
                [],
                [['classes' => 'class-one class-one class-two']],
                ['class' => 'class-one class-two'],
                'Should remove duplicates within the same string input.',
            ],

            // edge cases
            'complex modern CSS classes' => [
                [],
                [['classes' => 'sm:hover:bg-blue-500 md:focus:ring-[3px] lg:w-[calc(100%-2rem)]']],
                ['class' => 'sm:hover:bg-blue-500 md:focus:ring-[3px] lg:w-[calc(100%-2rem)]'],
                'Should add complex modern CSS framework classes.',
            ],
            'extremely long string with unique classes' => [
                [],
                [['classes' => implode(' ', array_map(static fn($i): string => "class-{$i}", range(1, 100)))]],
                ['class' => implode(' ', array_map(static fn($i): string => "class-{$i}", range(1, 100)))],
                'Should handle extremely long strings with many unique classes.',
            ],
            'extremely long whitespace sequence' => [
                [],
                [['classes' => str_repeat(' ', 10000)]],
                [],
                'Should handle extremely long whitespace sequence without errors.',
            ],
            'many classes at once' => [
                [],
                [
                    [
                        'classes' => [
                            'class-1',
                            'class-2',
                            'class-3',
                            'class-4',
                            'class-5',
                            'class-6',
                            'class-7',
                            'class-8',
                            'class-9',
                            'class-10',
                        ],
                    ],
                ],
                ['class' => 'class-1 class-2 class-3 class-4 class-5 class-6 class-7 class-8 class-9 class-10'],
                'Should handle adding many classes at once.',
            ],
            'stringable' => [
                [],
                [
                    ['classes' => new class implements Stringable {
                        public function __toString(): string
                        {
                            return 'error';
                        }
                    },
                    ],
                ],
                ['class' => 'error'],
                'Should handle stringable Enum values correctly.',
            ],
            'token containing backslash character' => [
                [],
                [['classes' => 'valid-class class\with\backslash another-valid']],
                ['class' => 'valid-class another-valid'],
                "Should drop tokens containing backslash '\\' character.",
            ],
            'token containing dollar sign character' => [
                [],
                [['classes' => 'valid-class class$with$dollar another-valid']],
                ['class' => 'valid-class another-valid'],
                "Should drop tokens containing dollar sign '$' character.",
            ],
            'token containing invalid characters' => [
                [],
                [['classes' => 'bad<class> bad@class bad!class']],
                [],
                "Should drop tokens containing '<', '>', '@', or '!' characters.",
            ],
            'unicode characters in square brackets' => [
                [],
                [['classes' => 'content-[\'★\'] before:[content:\'→\']']],
                ['class' => 'content-[\'★\'] before:[content:\'→\']'],
                'Should handle classes with unicode characters in square brackets.',
            ],
            'very long class name' => [
                [],
                [['classes' => 'very-long-class-name-with-many-hyphens-and-segments']],
                ['class' => 'very-long-class-name-with-many-hyphens-and-segments'],
                'Should handle very long class names.',
            ],

            // empty and null values
            'empty array' => [
                [],
                [['classes' => []]],
                [],
                'Should return empty array when adding empty array.',
            ],
            'empty string' => [
                [],
                [['classes' => '']],
                [],
                'Should return empty array when adding empty string.',
            ],
            'empty string to existing class' => [
                ['class' => 'existing-class'],
                [['classes' => '']],
                ['class' => 'existing-class'],
                'Should preserve existing class when adding empty string.',
            ],
            'null to existing class' => [
                ['class' => 'existing-class'],
                [['classes' => null]],
                ['class' => 'existing-class'],
                "Should preserve existing class when adding 'null' value.",
            ],
            'null value' => [
                [],
                [['classes' => null]],
                [],
                "Should return empty array when adding 'null' value.",
            ],

            // enum values
            'enum that returns int should be filtered' => [
                [],
                [['classes' => Priority::HIGH]],
                [],
                'Should filter out enum with int value.',
            ],
            'enum with existing class' => [
                ['class' => 'existing-class'],
                [['classes' => AlertType::SUCCESS]],
                ['class' => 'existing-class success'],
                'Should append enum value to existing classes.',
            ],
            'mixed strings and enums' => [
                [],
                [
                    [
                        'classes' => [
                            'class-one',
                            AlertType::SUCCESS,
                            'class-two',
                            AlertType::WARNING,
                        ],
                    ],
                ],
                ['class' => 'class-one success class-two warning'],
                'Should add classes from mixed string and enum array.',
            ],
            'multiple enums in array' => [
                [],
                [
                    [
                        'classes' => [
                            AlertType::SUCCESS,
                            AlertType::WARNING,
                            AlertType::ERROR,
                        ],
                    ],
                ],
                ['class' => 'success warning error'],
                'Should add multiple classes from enum array.',
            ],
            'single enum' => [
                [],
                [['classes' => AlertType::SUCCESS]],
                ['class' => 'success'],
                'Should add class from enum value.',
            ],

            // existing attributes preservation
            'add enum to attributes with other properties' => [
                [
                    'id' => 'button-id',
                    'type' => 'button',
                ],
                [['classes' => ButtonSize::LARGE]],
                [
                    'id' => 'button-id',
                    'type' => 'button',
                    'class' => 'lg',
                ],
                'Should preserve other attributes when adding enum class.',
            ],
            'preserve other attributes when adding class' => [
                [
                    'id' => 'element-id',
                    'data-value' => 'test',
                ],
                [['classes' => 'new-class']],
                [
                    'id' => 'element-id',
                    'data-value' => 'test',
                    'class' => 'new-class',
                ],
                'Should preserve other attributes when adding class.',
            ],
            'preserve other attributes with existing class' => [
                [
                    'id' => 'element-id',
                    'class' => 'existing-class',
                    'data-value' => 'test',
                ],
                [['classes' => 'new-class']],
                [
                    'id' => 'element-id',
                    'class' => 'existing-class new-class',
                    'data-value' => 'test',
                ],
                'Should preserve other attributes and append to existing class.',
            ],

            // override behavior
            'multiple operations with override in middle' => [
                [],
                [
                    ['classes' => 'class-one'],
                    ['classes' => 'class-two'],
                    [
                        'classes' => 'class-override',
                        'override' => true,
                    ],
                    ['classes' => 'class-three'],
                ],
                ['class' => 'class-override class-three'],
                'Should override previous classes and continue appending after override.',
            ],
            'override preserves other attributes' => [
                [
                    'class' => 'old-class',
                    'data-value' => 'test',
                    'id' => 'element-id',
                ],
                [
                    [
                        'classes' => 'new-class',
                        'override' => true,
                    ],
                ],
                [
                    'class' => 'new-class',
                    'data-value' => 'test',
                    'id' => 'element-id',
                ],
                'Should preserve other attributes when overriding class.',
            ],
            'override with array' => [
                ['class' => 'class-one class-two'],
                [
                    [
                        'classes' => [
                            'class-three',
                            'class-four',
                        ],
                        'override' => true,
                    ],
                ],
                ['class' => 'class-three class-four'],
                'Should override existing classes with new array values.',
            ],
            'override with empty string' => [
                ['class' => 'existing-class'],
                [
                    [
                        'classes' => '',
                        'override' => true,
                    ],
                ],
                ['class' => 'existing-class'],
                'Should not modify existing classes when overriding with empty string.',
            ],
            'override with enum' => [
                ['class' => 'existing-class'],
                [
                    [
                        'classes' => AlertType::SUCCESS,
                        'override' => true,
                    ],
                ],
                ['class' => 'success'],
                'Should override existing classes with enum value.',
            ],
            'override with null' => [
                ['class' => 'existing-class'],
                [
                    [
                        'classes' => null,
                        'override' => true,
                    ],
                ],
                ['class' => 'existing-class'],
                "Should not modify existing classes when overriding with 'null'.",
            ],
            'override with string' => [
                ['class' => 'class-one class-two'],
                [
                    [
                        'classes' => 'class-override',
                        'override' => true,
                    ],
                ],
                ['class' => 'class-override'],
                'Should override existing classes with new string value.',
            ],

            // single string values
            'multiple classes in single string' => [
                [],
                [['classes' => 'class-one class-two class-three']],
                ['class' => 'class-one class-two class-three'],
                'Should add multiple space-separated classes from single string.',
            ],
            'single class with spaces' => [
                [],
                [['classes' => '  class-one  ']],
                ['class' => 'class-one'],
                'Should trim spaces and add single class.',
            ],
            'single class' => [
                [],
                [['classes' => 'class-one']],
                ['class' => 'class-one'],
                'Should add a single class to empty attributes.',
            ],

            // special characters
            'classes with colons' => [
                [],
                [['classes' => 'hover:bg-blue-500 focus:ring-2 lg:text-xl']],
                ['class' => 'hover:bg-blue-500 focus:ring-2 lg:text-xl'],
                'Should add classes with colons for pseudo-class modifiers.',
            ],
            'classes with dots' => [
                [],
                [['classes' => 'utility.class namespace.component']],
                ['class' => 'utility.class namespace.component'],
                'Should add classes with dots.',
            ],
            'classes with hyphens' => [
                [],
                [['classes' => 'btn-primary btn-lg text-center']],
                ['class' => 'btn-primary btn-lg text-center'],
                'Should add classes with hyphens (kebab-case).',
            ],
            'classes with numbers' => [
                [],
                [['classes' => 'col-12 grid-3 z-50']],
                ['class' => 'col-12 grid-3 z-50'],
                'Should add classes with numbers.',
            ],
            'classes with square brackets' => [
                [],
                [['classes' => 'w-[200px] bg-[#1da1f2] p-[2rem]']],
                ['class' => 'w-[200px] bg-[#1da1f2] p-[2rem]'],
                'Should add classes with square brackets for arbitrary values.',
            ],
            'classes starting with hyphens' => [
                [],
                [['classes' => '-mt-4 -ml-2 --custom-var']],
                ['class' => '-mt-4 -ml-2 --custom-var'],
                'Should add classes starting with hyphens.',
            ],
            'classes with underscores' => [
                [],
                [['classes' => 'class_name _private another_class']],
                ['class' => 'class_name _private another_class'],
                'Should add classes with underscores.',
            ],

            // whitespace handling
            'classes with multiple spaces' => [
                [],
                [['classes' => 'class-one    class-two     class-three']],
                ['class' => 'class-one class-two class-three'],
                'Should normalize multiple spaces between classes.',
            ],
            'classes with tabs and newlines' => [
                [],
                [['classes' => "class-one\tclass-two\nclass-three"]],
                ['class' => 'class-one class-two class-three'],
                'Should handle tabs and newlines as separators.',
            ],
            'mixed whitespace types' => [
                [],
                [['classes' => "  \t  \n  \r  \v  \f  "]],
                [],
                'Should handle all types of whitespace characters.',
            ],
            'string with only whitespace' => [
                [],
                [['classes' => "   \t\n\r   "]],
                [],
                'Should return empty array for string with only whitespace characters.',
            ],
        ];
    }
}
