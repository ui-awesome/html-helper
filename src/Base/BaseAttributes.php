<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use Closure;
use UIAwesome\Html\Helper\{Encode, Enum};

use function array_map;
use function gettype;
use function implode;
use function is_array;
use function is_bool;
use function is_numeric;
use function is_string;
use function json_encode;
use function preg_match;
use function rtrim;
use function strtolower;
use function uksort;

/**
 * Base class for advanced HTML attribute rendering and encoding.
 *
 * Designed for use in tag rendering, this class ensures that all attributes are output in a predictable,
 * standards-compliant order, with correct escaping and type handling for modern HTML5 use cases.
 *
 * Key features.
 * - Array handling for `class`, `style`, `data-*`, and `aria-*` attributes.
 * - Attribute sorting by priority for readable, maintainable HTML.
 * - BackedEnum normalization for attribute values.
 * - Extensible for custom attribute types and rendering strategies.
 * - JSON encoding for complex attribute values.
 * - Secure HTML and value encoding to prevent XSS.
 * - Support for boolean attributes (for example, `checked`, `disabled`).
 * - Validation of attribute names using a strict regex pattern.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseAttributes
{
    /**
     * JSON encoding options for attribute values.
     *
     * Includes.
     * - Error handling.
     * - HTML entity encoding.
     * - Unicode handling.
     */
    private const JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS |
        JSON_THROW_ON_ERROR;

    /**
     * Maps HTML attributes to their rendering order priority.
     *
     * Lower numbers indicate higher priority in the rendered output.
     *
     * @phpstan-var array<string, int>
     */
    private const ORDER_MAP = [
        'class' => 0,
        'id' => 1,
        'name' => 2,
        'type' => 3,
        'http-equiv' => 4,
        'value' => 5,
        'href' => 6,
        'src' => 7,
        'for' => 8,
        'title' => 9,
        'alt' => 10,
        'role' => 11,
        'tabindex' => 12,
        'srcset' => 13,
        'form' => 14,
        'action' => 15,
        'method' => 16,
        'selected' => 17,
        'checked' => 18,
        'readonly' => 19,
        'disabled' => 20,
        'multiple' => 21,
        'size' => 22,
        'maxlength' => 23,
        'width' => 24,
        'height' => 25,
        'rows' => 26,
        'cols' => 27,
        'rel' => 28,
        'as' => 29,
        'media' => 30,
        'data' => 31,
        'style' => 32,
        'aria' => 33,
        'data-ng' => 34,
        'ng' => 35,
    ];

    /**
     * Quote characters for attribute values.
     */
    private const QUOTE_DOUBLE = '"';

    /**
     * Quote characters for attribute values.
     */
    private const QUOTE_SINGLE = '\'';

    /**
     * Regular expression pattern for validating attribute names.
     *
     * Validates that attribute names.
     * - Contain only letters, numbers, hyphens, and underscores.
     * - Start with a letter or underscore.
     */
    private const VALID_ATTRIBUTE_NAME_PATTERN = '/^[a-zA-Z_][a-zA-Z0-9_-]*$/';

    /**
     * Renders an array of HTML attributes into a standards-compliant string.
     *
     * Processes the provided associative array of attribute names and values, generating an escaped and consistently
     * ordered HTML attributes string for use in tag rendering.
     *
     * Attribute names are validated and sorted according to a predefined priority map to ensure predictable output.
     *
     * @param array $attributes Associative array of attribute names and values.
     *
     * @return string Rendered HTML attributes string.
     *
     * @phpstan-param mixed[] $attributes
     *
     * Usage example:
     * ```php
     * Attributes::render(
     *     [
     *         'type' => 'text',
     *         'name' => 'username',
     *         'required' => true,
     *         'class' => ['form-control'],
     *         'data-info' => ['role' => 'user', 'id' => 42],
     *     ],
     * );
     * // class="form-control" name="username" type="text" required data-info='{"role":"user","id":42}'
     * ```
     */
    public static function render(array $attributes): string
    {
        return self::renderInternal($attributes);
    }

    /**
     * Validates that an attribute name follows HTML naming rules.
     *
     * Ensures that the provided attribute name is non-empty and matches the allowed HTML attribute naming pattern.
     *
     * Accepts attribute names that are either explicitly listed in the {@see ORDER_MAP} or conform to the
     * {@see VALID_ATTRIBUTE_NAME_PATTERN} regular expression, which enforces starting with a letter or underscore and
     * allows only alphanumeric characters, hyphens, and underscores.
     *
     * This validation is essential for generating standards-compliant HTML and preventing injection of invalid or
     * unsafe attribute names during tag rendering.
     *
     * @param string $name Attribute name to validate.
     *
     * @return bool Whether the Attribute name is valid, according to HTML rules.
     */
    private static function isValidAttributeName(string $name): bool
    {
        return isset(self::ORDER_MAP[$name]) || preg_match(self::VALID_ATTRIBUTE_NAME_PATTERN, $name) === 1;
    }

    /**
     * Renders array-based HTML attributes into a standards-compliant string format.
     *
     * Converts array values for specific attribute types into their appropriate HTML string representations.
     * - Converts style attribute arrays into CSS declaration strings for the `style` attribute.
     * - Encodes other array attributes as JSON strings for safe embedding in HTML.
     * - Expands `data-*`, `aria-*` and similar attributes with proper prefixes and encoding, supporting nested arrays
     *   via JSON encoding.
     * - Merges class attribute arrays into a space-separated string for the `class` attribute.
     *
     * @param string $name Attribute name to render.
     * @param array $values Array of attribute values to process and render.
     *
     * @return string Rendered HTML attribute string suitable for direct inclusion in a tag.
     *
     * @phpstan-param array<array-key, mixed> $values
     */
    private static function renderArrayAttributes(string $name, array $values): string
    {
        return match ($name) {
            'class' => self::renderClassAttributes($values),
            'aria', 'data', 'data-ng', 'ng' => self::renderDataAttributes($name, $values),
            'style' => self::renderStyleAttributes($values),
            default => self::renderAttribute($name, json_encode($values, self::JSON_FLAGS), self::QUOTE_SINGLE),
        };
    }

    /**
     * Generates the string representation of a single HTML attribute for tag rendering.
     *
     * Handles both boolean attributes (rendered without a value, for example, `disabled`) and regular attributes with
     * values, supporting both single and double quote styles for value encapsulation.
     *
     * @param string $name Attribute name to render.
     * @param int|string $encodedValue Encoded attribute value (empty string for boolean attributes).
     * @param string $quote Quote character to use for value (default: {@see QUOTE_DOUBLE}).
     *
     * @return string Rendered HTML attribute string, ready for direct tag inclusion.
     */
    private static function renderAttribute(
        string $name,
        int|string $encodedValue = '',
        string $quote = self::QUOTE_DOUBLE,
    ): string {
        return $encodedValue === '' ? " {$name}" : " {$name}={$quote}{$encodedValue}{$quote}";
    }

    /**
     * Generates a standards-compliant HTML attribute string based on a value type.
     *
     * Processes the provided attribute name and value, rendering the output according to the value's type.
     * - Arrays are handled as complex attributes (for example, `class`, `style`, `data-*`, `aria-*`) and rendered using
     *   the appropriate formatting method.
     * - `BackedEnum` are normalized to their backing value for safe HTML output.
     * - Boolean values are rendered as boolean attributes (for example, `checked`, `disabled`) with presence indicating
     *   `true`.
     * - Empty strings and `null` values result in no output.
     * - Other types are encoded and rendered as regular HTML attributes.
     *
     * @param string $name Attribute name to render.
     * @param mixed $values Attribute value(s) to process and render. Accepts arrays, enums, booleans, or scalars.
     *
     * @return string Rendered HTML attribute string, ready for direct tag inclusion.
     */
    private static function renderAttributes(string $name, mixed $values): string
    {
        $values = self::sanitizeJsonValue($values);

        if ($values === '' || $values === null) {
            return '';
        }

        if (is_bool($values)) {
            return self::renderBooleanAttributes($name, $values);
        }

        return match (gettype($values)) {
            'array' => self::renderArrayAttributes($name, $values),
            'string' => self::renderAttribute($name, $values),
            default => self::renderAttribute($name, json_encode($values, self::JSON_FLAGS)),
        };
    }

    /**
     * Renders a boolean HTML attribute for tag output.
     *
     * Generates the string representation of a boolean attribute (such as `checked`, `disabled`, or `required`) for use
     * in HTML tags, following the HTML5 convention where the presence of the attribute indicates `true` and its absence
     * indicates `false`.
     *
     * @param string $name Attribute name to render.
     * @param bool $value Boolean value indicating whether to render the attribute.
     *
     * @return string Rendered attribute string (with leading space) or an empty `string` if `false`.
     */
    private static function renderBooleanAttributes(string $name, bool $value): string
    {
        return $value ? " {$name}" : '';
    }

    /**
     * Generates the string representation of the `class` HTML attribute for tag output.
     *
     * Joins class names provided as an array into a single space-separated string, encodes the result for safe HTML
     * output and returns a formatted `class` attribute suitable for direct inclusion in an HTML tag.
     *
     * Supports `BackedEnum` values within the class array, ensuring all values are normalized and encoded to prevent
     * XSS or malformed output.
     *
     * @param array $values Array of class names.
     *
     * @return string Rendered class attribute string, or an empty string if no classes are present.
     *
     * @phpstan-param array<array-key, mixed> $values
     */
    private static function renderClassAttributes(array $values): string
    {
        if ($values === []) {
            return '';
        }

        return self::renderAttribute('class', implode(' ', $values));
    }

    /**
     * Renders `data-*` and `aria-* HTML attributes as a standards-compliant string.
     *
     * Processes associative arrays of data or ARIA attributes, handling nested arrays by JSON encoding their values and
     * encoding simple values for safe HTML output. Supports `BackedEnum` values for attribute content.
     *
     * This method is used to generate attribute strings for `data-*` and `aria-*` attributes, ensuring that complex
     * values are encoded and that attribute names are correctly prefixed. Nested arrays are encoded as JSON using the
     * configured encoding flags, while scalar values are encoded for HTML safety. The output is suitable for direct
     * inclusion in HTML tags.
     *
     * @param string $name Attribute prefix (for example, `data` or `aria`).
     * @param array $values Associative array of attribute names and values to render.
     *
     * @return string Rendered data/ARIA attributes string, ready for HTML tag inclusion.
     *
     * @phpstan-param array<array-key, mixed> $values
     */
    private static function renderDataAttributes(string $name, array $values): string
    {
        $result = '';

        foreach ($values as $n => $v) {
            if (is_string($n) && self::isValidAttributeName($n)) {
                $result .= match (gettype($v)) {
                    'array' => self::renderAttribute(
                        "{$name}-{$n}",
                        json_encode($v, self::JSON_FLAGS),
                        self::QUOTE_SINGLE,
                    ),
                    'double', 'integer', 'string' => self::renderAttribute(
                        "{$name}-{$n}",
                        Encode::value($v),
                    ),
                    default => '',
                };
            }
        }

        return $result;
    }

    /**
     * Renders a complete HTML attributes string from an associative array.
     *
     * Iterates over the provided attributes, sorts them according to the {@see ORDER_MAP} priority and applies the
     * correct rendering strategy for each attribute type (scalar, array, boolean, or enum).
     *
     * Only valid attribute names are included in the output.
     *
     * @param array $attributes Associative array of attribute names and values.
     *
     * @return string Complete HTML attributes string, ready for tag output.
     *
     * @phpstan-param mixed[] $attributes
     */
    private static function renderInternal(array $attributes): string
    {
        $html = '';

        $sorted = self::sortAttributes($attributes);

        foreach ($sorted as $name => $values) {
            if (is_string($name) && $values !== '' && $values !== null && self::isValidAttributeName($name)) {
                $html .= self::renderAttributes($name, $values);
            }
        }

        return $html;
    }

    /**
     * Generates the string representation of the `style` HTML attribute for tag output.
     *
     * Converts an associative array of CSS property-value pairs into a single CSS declaration string, suitable for use
     * as the value of a `style` attribute in HTML tags. Each property and value is concatenated in the format
     * `property: value;` and the result is trimmed and encoded for safe HTML output.
     *
     * Supports `BackedEnum` values for CSS properties, ensuring all values are normalized and encoded to prevent XSS or
     * malformed output. Returns an empty string if no style properties are present.
     *
     * @param array $values Associative array of CSS property-value pairs.
     *
     * @return string Rendered style attribute string, or an empty string if no styles are present.
     *
     * @phpstan-param array<array-key, mixed> $values
     */
    private static function renderStyleAttributes(array $values): string
    {
        $result = '';

        foreach ($values as $n => $v) {
            if ($v !== null) {
                $prop = Encode::value((string) $n);
                $stringValue = is_string($v) || is_numeric($v) ? $v : json_encode($v, self::JSON_FLAGS);

                if ($stringValue !== '') {
                    $result .= "{$prop}: {$stringValue}; ";
                }
            }
        }

        return $result === '' ? '' : self::renderAttribute('style', rtrim($result));
    }

    /**
     * Sanitizes a value for safe JSON encoding in HTML attributes.
     *
     * Recursively prepares values for JSON encoding to ensure safe and standards-compliant HTML attribute output.
     *
     * This method processes arrays, strings, `Closure` and `BackedEnum` values to guarantee that all data embedded in
     * HTML attributes is encoded and normalized.
     *
     * Arrays are traversed recursively, strings are HTML-encoded, `BackedEnum` values are converted to their backing
     * value, and `Closure` are executed and their result is used.
     *
     * This prevents XSS vulnerabilities and ensures that complex attribute values are represented safely in the
     * rendered HTML.
     *
     * @param mixed $value Value to sanitize for JSON encoding. Accepts arrays, strings, enums, scalars or `Closure`.
     *
     * @return mixed Sanitized value, ready for safe HTML attribute embedding. Maybe a scalar, array, or the result of
     * a `Closure`.
     */
    private static function sanitizeJsonValue(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(static fn(mixed $v): mixed => self::sanitizeJsonValue($v), $value);
        }

        if (is_string($value)) {
            return Encode::value($value);
        }

        if ($value instanceof Closure) {
            return self::sanitizeJsonValue($value());
        }

        $normalized = Enum::normalizeValue($value);

        if (is_string($normalized)) {
            return Encode::value(strtolower($normalized));
        }

        return $normalized;
    }

    /**
     * Sorts HTML attributes by predefined priority for consistent output order.
     *
     * Reorders the provided associative array of attribute names and values according to the {@see ORDER_MAP} priority
     * mapping.
     *
     * Attributes with a defined priority are sorted before those without ensuring that commonly used attributes (such
     * as `class`, `id`, `name`, etc.) appear first in the rendered HTML output.
     *
     * Attributes not present in the priority map are ordered after the prioritized attributes, preserving their
     * original order among themselves.
     *
     * @param array $attributes Associative array of attribute names and values to sort.
     *
     * @return array Sorted an associative array of attributes, ready for rendering.
     *
     * @phpstan-param mixed[] $attributes
     * @phpstan-return mixed[]
     */
    private static function sortAttributes(array $attributes): array
    {
        uksort(
            $attributes,
            static fn(
                string $a,
                string $b,
            ) => (self::ORDER_MAP[$a] ?? PHP_INT_MAX) <=> (self::ORDER_MAP[$b] ?? PHP_INT_MAX),
        );

        return $attributes;
    }
}
