<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use InvalidArgumentException;
use UIAwesome\Html\Helper\Exception\Message;

use function mb_strtolower;
use function preg_match;
use function preg_replace;
use function str_ends_with;
use function str_replace;
use function strlen;
use function strrchr;
use function strrpos;
use function strtolower;
use function substr;
use function uniqid;

/**
 * Base class for form naming, identifier generation, and regular-expression utilities.
 *
 * Provides a concise set of stateless helper methods used by form builders, tag renderers and view helpers to produce
 * predictable, standards-compliant HTML input names and identifiers, to parse complex property notation (including
 * tabular inputs), and to convert regular expression literals to usable pattern strings.
 *
 * Key features.
 * - Conversion of regular expression literals to pattern substrings suitable for client-side `pattern` attribute and
 *   validation routines.
 * - Generation of arrayable input names and unique identifiers compatible with HTML form conventions.
 * - Parsing of property notation into discrete name, prefix and suffix components for tabular and nested inputs.
 * - Utility to produce short, optionally lowercased class names for use in templates and references.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */

abstract class BaseNaming
{
    /**
     * Converts a regular expression literal to its pattern substring.
     *
     * Extracts the pattern portion from a regular expression literal, converting any `\\x{...}` hex escapes to Unicode
     * `\u` notation. The optional delimiter may be inferred from the literal when not provided.
     *
     * @param string $regexp Regular expression literal to convert (including delimiters and flags).
     * @param string|null $delimiter Optional delimiter character. If `null`, the delimiter is inferred from the
     * provided literal when possible.
     *
     * @throws InvalidArgumentException if the delimiter is not provided and cannot be inferred, if the literal is too
     * short to contain a pattern, or if the regular expression is malformed.
     *
     * @return string Pattern substring extracted from the regular expression literal.
     */
    public static function convertToPattern(string $regexp, string|null $delimiter = null): string
    {
        if (strlen($regexp) < 2) {
            throw new InvalidArgumentException(
                Message::LENGTH_LESS_THAN_TWO->getMessage(),
            );
        }

        if ($delimiter === null) {
            $delimiter = $regexp[0] ?? '';
        }

        if (strlen($delimiter) !== 1) {
            throw new InvalidArgumentException(
                Message::INCORRECT_DELIMITER->getMessage(),
            );
        }

        $pattern = preg_replace('/\\\\x{?([0-9a-fA-F]+)}?/', '\u$1', $regexp);
        $endPosition = strrpos((string) $pattern, $delimiter, 1);

        if ($endPosition === false) {
            throw new InvalidArgumentException(
                Message::INCORRECT_REGEXP->getMessage(),
            );
        }

        return substr((string) $pattern, 1, $endPosition - 1);
    }

    /**
     * Ensure a form property name is arrayable (ends with `[]`).
     *
     * Returns the original name if it already ends with `[]`, otherwise appends `[]` to make it arrayable for tabular
     * or multiple-value inputs.
     *
     * @param string $name Base property name.
     *
     * @return string Arrayable property name.
     */
    public static function generateArrayableName(string $name): string
    {
        return str_ends_with($name, '[]') === false ? $name . '[]' : $name;
    }

    /**
     * Generate a unique identifier suitable for use as an HTML `id` attribute.
     *
     * Uses a lightweight unique prefixing strategy appropriate for template generation and helper utilities.
     *
     * @param string $prefix Prefix to prepend to the generated identifier.
     *
     * @return string Unique identifier string.
     */
    public static function generateId(string $prefix = 'id-'): string
    {
        return uniqid($prefix);
    }

    /**
     * Generate an input element id from a form model and property name.
     *
     * Produces a deterministic id by lowercasing the generated input name and replacing array and bracket characters
     * with hyphens, ensuring the result is suitable for use as an HTML `id` attribute.
     *
     * @param string $formModel Form model name (maybe empty for non-tabular inputs).
     * @param string $property Property name or complex property notation (for example `items[0][name]`).
     * @param string $charset Character set used by `mb_strtolower` (default: `UTF-8`).
     *
     * @return string Generated id string safe for use in HTML attributes.
     */
    public static function generateInputId(
        string $formModel = '',
        string $property = '',
        string $charset = 'UTF-8',
    ): string {
        $name = mb_strtolower(self::generateInputName($formModel, $property), $charset);

        return str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], $name);
    }

    /**
     * Generate a fully-qualified input name for form fields.
     *
     * Builds a form input `name` value from a form model and a property. Supports arrayable properties and tabular
     * inputs by parsing property prefixes and suffixes.
     *
     * @param string $formModel Name of the form model (maybe empty for simple inputs).
     * @param string $property Property name or complex property notation.
     * @param bool $arrayable Whether to force the resulting name to be arrayable (append `[]`).
     *
     * @throws InvalidArgumentException if a tabular input is requested and the form model name is empty.
     *
     * @return string Generated input name suitable for use in HTML form `name` attributes.
     */
    public static function generateInputName(string $formModel, string $property, bool $arrayable = false): string
    {
        if ($arrayable === true) {
            $property = self::generateArrayableName($property);
        }

        $data = self::parseProperty($property);

        if ($formModel === '' && $data['prefix'] === '') {
            return $property;
        }

        if ($formModel !== '') {
            return $formModel . $data['prefix'] . '[' . $data['name'] . ']' . $data['suffix'];
        }

        throw new InvalidArgumentException(
            Message::FORM_MODEL_NAME_CANNOT_BE_EMPTY->getMessage(),
        );
    }

    /**
     * Return the short class name, optionally formatted for `::class` usage.
     *
     * Extracts the short portion of a fully-qualified class name and optionally returns it as a class constant
     * reference (`ShortName::class`). Optionally lowercases the input before extraction.
     *
     * @param string $class Fully-qualified class name.
     * @param bool $suffix Whether to append `::class` to the returned short name (default: `true`).
     * @param bool $lowercase Whether to lowercase the class name before extracting the short name.
     *
     * @return string Short class name or `ShortName::class` when `$suffix` is `true`.
     */
    public static function getShortNameClass(string $class, bool $suffix = true, bool $lowercase = false): string
    {
        if ($lowercase === true) {
            $class = strtolower($class);
        }

        $pos = strrchr($class, '\\');

        if ($pos !== false) {
            $class = substr($pos, 1);
        }

        return $suffix === true ? "$class::class" : $class;
    }

    /**
     * Parse a complex property notation into discrete components.
     *
     * Parses property expressions used in tabular and nested inputs into an associative array with keys `name`,
     * `prefix` and `suffix`. The result is used to compose fully-qualified input names.
     *
     * @param string $property Property expression to parse.
     *
     * @throws InvalidArgumentException if the property cannot be matched to the expected token pattern.
     *
     * @phpstan-return array{name: string, prefix: string, suffix: string} Parsed property components.
     */
    private static function parseProperty(string $property): array
    {
        if (preg_match('/(^|.*])([\w.+\-_]+)(\[.*|$)/u', $property, $matches) !== 1) {
            throw new InvalidArgumentException(
                Message::CANNOT_PARSE_PROPERTY->getMessage($property),
            );
        }

        return [
            'name' => $matches[2],
            'prefix' => $matches[1],
            'suffix' => $matches[3],
        ];
    }
}
