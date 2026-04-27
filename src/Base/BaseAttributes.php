<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use Closure;
use UIAwesome\Html\Helper\{Encode, Enum};

use function array_map;
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
 * Provides reusable normalization and rendering for HTML attribute arrays.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseAttributes
{
    /**
     * JSON flags for encoded attribute values.
     */
    private const int JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS
        | JSON_THROW_ON_ERROR;

    /**
     * JSON flags for raw attribute values.
     */
    private const int JSON_FLAGS_RAW = JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR;

    /**
     * @var array<string, int> Maps HTML attributes to their rendering order priority.
     *
     * Lower numbers indicate higher priority in the rendered output.
     */
    private const array ORDER_MAP = [
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
        'on' => 34,
        'data-ng' => 35,
        'ng' => 36,
    ];

    /**
     * Double quote character for attribute rendering.
     */
    private const string QUOTE_DOUBLE = '"';

    /**
     * Single quote character for attribute rendering.
     */
    private const string QUOTE_SINGLE = '\'';

    /**
     * Regular expression for valid attribute names.
     */
    private const string VALID_ATTRIBUTE_NAME_PATTERN = '/^[a-zA-Z_][a-zA-Z0-9_-]*$/';

    /**
     * Normalizes an array of HTML attributes into a flat associative array with processed values.
     *
     * Returns a normalized array instead of an HTML string.
     *
     * Usage example:
     * ```php
     * $normalized = \UIAwesome\Html\Helper\Attributes::normalizeAttributes(
     *     [
     *         'required' => true,
     *         'class' => ['form-control', 'input-lg'],
     *         'data' => ['id' => 42],
     *     ],
     * );
     * // ['class' => 'form-control input-lg', 'data-id' => '42', 'required' => true]
     * ```
     *
     * @param mixed[] $attributes Associative array of attribute names and values.
     * @param bool $encode Whether to HTML-encode `string` values.
     *
     * @return array<string, bool|string> Flat associative array with normalized values. Boolean attributes may return
     * `true`.
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
     * Renders an array of HTML attributes into a string.
     *
     * Validates names, normalizes values, and returns attributes in a stable order.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\Attributes::render(['name' => 'username', 'required' => true]);
     * // name="username" required
     * ```
     *
     * @param mixed[] $attributes Associative array of attribute names and values.
     *
     * @return string Rendered HTML attributes string.
     */
    public static function render(array $attributes): string
    {
        return self::renderInternal($attributes);
    }

    /**
     * Expands namespaced attributes into flat key-value pairs.
     *
     * @param mixed[] $values Associative array of attribute names and values.
     * @param string $prefix Attribute namespace prefix (for example, `data`, `aria`, `on`).
     * @param bool $encode Whether to HTML-encode string values.
     * @param bool $dashedPrefix Whether to use dashed key format (`prefix-key`) or concatenated format
     * (`prefixkey`).
     *
     * @return array<string, string> Associative array of expanded attribute key-value pairs.
     */
    private static function expandPrefixedAttributes(
        array $values,
        string $prefix,
        bool $encode,
        bool $dashedPrefix = true,
    ): array {
        $result = [];
        $flags = $encode ? self::JSON_FLAGS : self::JSON_FLAGS_RAW;

        foreach ($values as $n => $v) {
            if ($v !== null && is_string($n) && self::isValidAttributeName($n)) {
                $key = $dashedPrefix
                    ? "{$prefix}-{$n}"
                    : (str_starts_with($n, $prefix) ? $n : "{$prefix}{$n}");

                if (self::isValidAttributeName($key)) {
                    $result[$key] = (is_string($v) || is_numeric($v) ? (string) $v : json_encode($v, $flags));
                }
            }
        }

        return $result;
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
     * This validation is used to reject invalid attribute names during tag rendering.
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
     * - Returns `true` for `bool` attributes to distinguish them from `string` values.
     * - Returns a `string` for standard attributes.
     * - Returns an `array` for attributes that expand into multiple keys (like `data-*`).
     *
     * @param string $name Attribute name.
     * @param mixed $values Attribute value to normalize.
     * @param bool $encode Whether to HTML-encode string values.
     *
     * @return array<string, string>|bool|string Normalized attribute value(s).
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
                'aria', 'data', 'data-ng', 'ng' => self::expandPrefixedAttributes($values, $name, $encode),
                'on' => self::expandPrefixedAttributes($values, 'on', $encode, false),
                'style' => self::normalizeStyleValue($values, $encode),
                default => json_encode($values, $flags),
            };
        }

        return is_string($values) ? $values : json_encode($values, $flags);
    }

    /**
     * Normalizes a class list item to its renderable string representation.
     *
     * @param mixed $value Class list item.
     *
     * @return string Renderable class list item.
     */
    private static function normalizeClassItem(mixed $value): string
    {
        $normalized = Enum::normalizeValue($value);

        if (is_array($normalized)) {
            return json_encode($normalized, self::JSON_FLAGS);
        }

        return (string) $normalized;
    }

    /**
     * Normalizes class attribute values into a space-separated string.
     *
     * @param mixed[] $values Array of class names.
     *
     * @return string Space-separated class names, or empty string if no classes.
     */
    private static function normalizeClassValue(array $values): string
    {
        return $values === [] ? '' : implode(' ', array_map(self::normalizeClassItem(...), $values));
    }

    /**
     * Normalizes style attribute values into a CSS declaration string.
     *
     * @param mixed[] $values Associative array of CSS property-value pairs.
     * @param bool $encode Whether to HTML-encode string values.
     *
     * @return string CSS declaration string, or empty string if no styles.
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
     * @param bool|string $value Attribute value. If `true`, renders as a boolean attribute (for example, `checked`).
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
     * @param mixed[] $attributes Associative array of attribute names and values.
     *
     * @return string Complete HTML attributes `string`, ready for tag output.
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
     * Recursively prepares values. If `$encode` is `true`, `string` values are HTML-encoded. If `$encode` is `false`,
     * values are returned raw (useful for DOM manipulation where the engine handles escaping).
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
     * @param mixed[] $attributes Associative array of attribute names and values to sort.
     *
     * @return mixed[] Sorted an associative array of attributes, ready for rendering.
     */
    private static function sortAttributes(array $attributes): array
    {
        uksort(
            $attributes,
            static fn(
                string $a,
                string $b,
            ): int => (self::ORDER_MAP[$a] ?? PHP_INT_MAX) <=> (self::ORDER_MAP[$b] ?? PHP_INT_MAX),
        );

        return $attributes;
    }
}
