<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use Closure;
use InvalidArgumentException;
use UIAwesome\Html\Helper\{Encode, Enum};
use UIAwesome\Html\Helper\Exception\Message;

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
use function str_starts_with;
use function uksort;

/**
 * Base class for advanced HTML attribute rendering and encoding.
 *
 * Designed for use in tag rendering and normalization of HTML attributes key-value pairs, this class ensures that all
 * attributes are output in a predictable, standards-compliant order, with correct escaping and type handling for modern
 * HTML5 use cases.
 *
 * It acts as a central engine for both generating HTML strings and preparing attributes for DOM manipulation (like SVG),
 * offering flexible control over encoding.
 *
 * Key features.
 * - **Double-Encoding Prevention:** Optional control over HTML entity encoding to prevent issues when using
 *   DOMDocument.
 * - **Dual Mode:** Supports both HTML string rendering (securely encoded) and raw data normalization (unencoded for
 *   DOM/SVG).
 * - **Smart Boolean Handling:** Distinguishes between boolean attributes (returning `true`) and string values during
 *   normalization.
 * - Array handling for `class`, `style`, `data-*`, `aria-*`, and `on*` attributes.
 * - Attribute sorting by priority for readable, maintainable HTML.
 * - BackedEnum normalization for attribute values.
 * - Extensible for custom attribute types and rendering strategies.
 * - JSON encoding for complex attribute values.
 * - Normalization of attribute keys with specific prefixes.
 * - Secure HTML and value encoding to prevent XSS.
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
    private const JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS
        | JSON_THROW_ON_ERROR;

    /**
     * JSON encoding options for raw attribute values (no HTML encoding).
     *
     * Includes.
     * - Error handling.
     * - Unicode handling.
     */
    private const JSON_FLAGS_RAW = JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR;

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
     * Normalizes an array of HTML attributes into a flat associative array with processed values.
     *
     * Processes the provided associative array of attribute names and values, applying normalization logic similar to
     * {@see render()}, but returns a flat array instead of an HTML string.
     *
     * @param array $attributes Associative array of attribute names and values.
     * @param bool $encode Whether to HTML-encode string values. Set to `false` when using with DOMDocument to avoid
     * double escaping (entities like `&` becoming `&amp;amp;`). Default: `true`.
     *
     * @return array Flat associative array with normalized attribute values. Boolean attributes may return `true`
     * (bool) to verify their existence without rendering a string value.
     *
     * @phpstan-param mixed[] $attributes
     * @phpstan-return array<string, string|bool>
     *
     * Usage example:
     * ```php
     * Attributes::normalizeAttributes(
     *     [
     *         'type' => 'text',
     *         'name' => 'username',
     *         'required' => true,
     *         'class' => ['form-control', 'input-lg'],
     *         'style' => ['color' => 'red', 'font-size' => '14px'],
     *         'data' => ['id' => 42, 'role' => 'user'],
     *         'data-info' => ['nested' => ['key' => 'value']],
     *     ],
     * );
     * // [
     * //     'class' => 'form-control input-lg',
     * //     'name' => 'username',
     * //     'type' => 'text',
     * //     'required' => 'required',
     * //     'style' => 'color: red; font-size: 14px;',
     * //     'data-id' => '42',
     * //     'data-role' => 'user',
     * //     'data-info' => '{"nested":{"key":"value"}}',
     * // ]
     * ```
     */
    public static function normalizeAttributes(array $attributes, bool $encode = true): array
    {
        $normalized = [];
        $sorted = self::sortAttributes($attributes);

        foreach ($sorted as $name => $values) {
            if (is_string($name) && $values !== '' && $values !== null && self::isValidAttributeName($name)) {
                $processed = self::normalizeAttributeValue($name, $values, $encode);

                if (is_array($processed)) {
                    foreach ($processed as $key => $value) {
                        $normalized[$key] = $value;
                    }
                } elseif ($processed !== '') {
                    $normalized[$name] = $processed;
                }
            }
        }

        return $normalized;
    }

    /**
     * Normalizes an attribute key ensuring it has a specific prefix.
     *
     * Validates the provided key, normalizes it (handling UnitEnum and Stringable), and ensures the specified prefix is
     * present. If the key already starts with the prefix, it is returned as-is; otherwise, the prefix is prepended.
     *
     * This method is particularly useful for standardizing attributes like `aria-*`, `data-*`, or event handlers
     * (for example, `onclick`).
     *
     * @param mixed $key Key to normalize. Accepts strings, Stringable objects, or UnitEnum cases.
     * @param string $prefix Prefix to ensure (for example, 'aria-', 'data-', 'on').
     *
     * @throws InvalidArgumentException if the key is empty, not a string, or cannot be normalized to a string.
     *
     * @return string Normalized key with the prefix ensured.
     *
     * Usage example:
     * ```php
     * // standard string normalization
     * Attributes::normalizeKey('label', 'aria-');
     * // 'aria-label'
     *
     * // handling existing prefix
     * Attributes::normalizeKey('data-id', 'data-');
     * // 'data-id'
     *
     * // using UnitEnum
     * Attributes::normalizeKey(MyEnum::CLICK, 'on');
     * // 'onclick'
     * ```
     */
    public static function normalizeKey(mixed $key, string $prefix): string
    {
        $normalizedKey = Enum::normalizeValue($key);

        if ($normalizedKey === '' || is_string($normalizedKey) === false) {
            throw new InvalidArgumentException(
                Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage(),
            );
        }

        if (str_starts_with($normalizedKey, $prefix) === false) {
            return "{$prefix}{$normalizedKey}";
        }

        return $normalizedKey;
    }

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
     * Normalizes a single attribute value into its processed form.
     *
     * Applies transformation logic based on the attribute name and value type.
     * - Returns `true` (bool) for boolean attributes to distinguish them from string values.
     * - Returns a string for standard attributes.
     * - Returns an array for attributes that expand into multiple keys (like `data-*`).
     *
     * @param string $name Attribute name.
     * @param mixed $values Attribute value to normalize.
     * @param bool $encode Whether to HTML-encode string values.
     *
     * @return array|bool|string Normalized attribute value(s).
     *
     * @phpstan-return array<string, string>|bool|string
     */
    private static function normalizeAttributeValue(string $name, mixed $values, bool $encode): array|string|bool
    {
        $values = self::sanitizeJsonValue($values, $encode);

        if ($values === '' || $values === null) {
            return '';
        }

        if (is_bool($values)) {
            return $values ? true : '';
        }

        $flags = $encode ? self::JSON_FLAGS : self::JSON_FLAGS_RAW;

        if (is_array($values)) {
            return match ($name) {
                'class' => self::normalizeClassValue($values),
                'aria', 'data', 'data-ng', 'ng' => self::normalizeDataValue($name, $values, $encode),
                'style' => self::normalizeStyleValue($values, $encode),
                default => json_encode($values, $flags),
            };
        }

        return is_string($values) ? $values : json_encode($values, $flags);
    }

    /**
     * Normalizes class attribute values into a space-separated string.
     *
     * @param array $values Array of class names.
     *
     * @return string Space-separated class names, or empty string if no classes.
     *
     * @phpstan-param mixed[] $values
     */
    private static function normalizeClassValue(array $values): string
    {
        return $values === [] ? '' : implode(' ', $values);
    }

    /**
     * Normalizes `data-*`, `aria-*` and similar prefixed attributes into an expanded associative array.
     *
     * @param string $name Attribute prefix (for example, `data`, `aria`).
     * @param array $values Associative array of attribute names and values.
     * @param bool $encode Whether to HTML-encode string values.
     *
     * @return array Associative array of expanded attribute key-value pairs.
     *
     * @phpstan-param mixed[] $values
     * @phpstan-return array<string, string>
     */
    private static function normalizeDataValue(string $name, array $values, bool $encode): array
    {
        $result = [];
        $flags = $encode ? self::JSON_FLAGS : self::JSON_FLAGS_RAW;

        foreach ($values as $n => $v) {
            if ($v === null) {
                continue;
            }

            if (is_string($n) && self::isValidAttributeName($n)) {
                $key = "{$name}-{$n}";

                $result[$key] = match (gettype($v)) {
                    'array' => json_encode($v, $flags),
                    'double', 'integer', 'string' => (string) $v,
                    default => '',
                };
            }
        }

        return $result;
    }

    /**
     * Normalizes style attribute values into a CSS declaration string.
     *
     * @param array $values Associative array of CSS property-value pairs.
     * @param bool $encode Whether to HTML-encode string values.
     *
     * @return string CSS declaration string, or empty string if no styles.
     *
     * @phpstan-param mixed[] $values
     */
    private static function normalizeStyleValue(array $values, bool $encode): string
    {
        $result = '';
        $flags = $encode ? self::JSON_FLAGS : self::JSON_FLAGS_RAW;

        foreach ($values as $n => $v) {
            if ($v !== null) {
                $prop = $encode ? Encode::value($n) : $n;
                $val = '';

                if (is_string($v) || is_numeric($v)) {
                    $val = $v;
                } else {
                    $val = json_encode($v, $flags);
                }

                if ($val !== '') {
                    $result .= "{$prop}: {$val}; ";
                }
            }
        }

        return rtrim($result);
    }

    /**
     * Generates the string representation of a single HTML attribute.
     *
     * Intelligently handles boolean attributes (rendering only the name) and quotes (switching to single quotes for
     * JSON or style values).
     *
     * @param string $name Attribute name.
     * @param bool|string $value Attribute value. If `true`, renders as a boolean attribute (e.g., `checked`).
     *
     * @return string Rendered HTML attribute string.
     */
    private static function renderAttribute(string $name, bool|string $value): string
    {
        if ($value === true) {
            return " {$name}";
        }

        $quote = self::QUOTE_DOUBLE;

        if (
            $name === 'style'
            || is_string($value)
            && (str_starts_with($value, '{') || str_starts_with($value, '['))
        ) {
            $quote = self::QUOTE_SINGLE;
        }

        return " {$name}={$quote}{$value}{$quote}";
    }

    /**
     * Renders a complete HTML attributes string from an associative array.
     *
     * Uses {@see normalizeAttributes()} internally to process values ensuring consistency between HTML string rendering
     * and DOM manipulation.
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
        $normalized = self::normalizeAttributes($attributes, true);

        foreach ($normalized as $name => $value) {
            $html .= self::renderAttribute($name, $value);
        }

        return $html;
    }

    /**
     * Sanitizes a value for safe JSON encoding or HTML output.
     *
     * Recursively prepares values. If `$encode` is `true`, string values are HTML-encoded to prevent XSS. If `$encode`
     * is `false`, values are returned raw (useful for DOM manipulation where the engine handles escaping).
     *
     * @param mixed $value Value to sanitize.
     * @param bool $encode Whether to HTML-encode string values.
     *
     * @return mixed Sanitized value.
     */
    private static function sanitizeJsonValue(mixed $value, bool $encode): mixed
    {
        if (is_array($value)) {
            return array_map(static fn(mixed $v): mixed => self::sanitizeJsonValue($v, $encode), $value);
        }

        if ($value instanceof Closure) {
            return self::sanitizeJsonValue($value(), $encode);
        }

        $normalized = Enum::normalizeValue($value);

        if (is_string($normalized)) {
            return $encode ? Encode::value($normalized) : $normalized;
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
