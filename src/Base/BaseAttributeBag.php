<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use Closure;
use InvalidArgumentException;
use UIAwesome\Html\Helper\Enum;
use UIAwesome\Html\Helper\Exception\Message;
use UnitEnum;

use function array_key_exists;
use function is_bool;
use function is_string;
use function preg_match;
use function str_starts_with;

/**
 * Provides reusable operations for associative HTML attribute bags.
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseAttributeBag
{
    /**
     * Returns an attribute value by key.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\AttributeBag::get($attributes, 'id');
     * \UIAwesome\Html\Helper\AttributeBag::get($attributes, 'type', 'button');
     * \UIAwesome\Html\Helper\AttributeBag::get($attributes, 'label', null, 'aria-');
     * ```
     *
     * @param array $attributes Attribute bag.
     * @param string|UnitEnum $key Attribute key.
     * @param mixed $default Default value when key is not present.
     * @param string $prefix Prefix to ensure (for example, `aria-`, `data-`, `on`).
     *
     * @throws InvalidArgumentException if normalized key is not a non-empty `string`.
     *
     * @return mixed Attribute value or default.
     *
     * @phpstan-param mixed[] $attributes
     */
    public static function get(
        array $attributes,
        string|UnitEnum $key,
        mixed $default = null,
        string $prefix = '',
    ): mixed {
        $normalizedKey = self::normalizeKey($key, $prefix);

        return array_key_exists($normalizedKey, $attributes)
            ? $attributes[$normalizedKey]
            : $default;
    }

    /**
     * Merges values into the attribute bag.
     *
     * Existing keys are overridden by values from `$values`.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\AttributeBag::merge(
     *     $attributes,
     *     ['type' => 'submit', 'disabled' => true],
     * );
     * ```
     *
     * **Note:** This method does NOT:
     * - Filter `null` values (`null` will remain in the bag).
     * - Normalize keys (enum keys should be pre-normalized).
     * - Resolve closures or apply prefixes.
     *
     * @param array $attributes Attribute bag to update in place.
     * @param array $values Values to merge into the bag.
     *
     * @phpstan-param mixed[] $attributes
     * @phpstan-param mixed[] $values
     */
    public static function merge(array &$attributes, array $values): void
    {
        $attributes = [...$attributes, ...$values];
    }

    /**
     * Normalizes an attribute key ensuring it has a specific prefix.
     *
     * Returns the key unchanged when it already has the prefix.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\AttributeBag::normalizeKey('label', 'aria-');
     * // 'aria-label'
     * ```
     *
     * @param mixed $key Key to normalize. Accepts strings, Stringable objects, or UnitEnum cases.
     * @param string $prefix Prefix to ensure (for example, `aria-`, `data-`, `on`).
     *
     * @throws InvalidArgumentException if the key is empty, not a `string`, or cannot be normalized to a `string`.
     *
     * @return string Normalized key with the prefix ensured.
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
     * Removes an attribute from the bag.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\AttributeBag::remove($attributes, 'disabled');
     * \UIAwesome\Html\Helper\AttributeBag::remove($attributes, 'label', 'aria-');
     * ```
     *
     * @param array $attributes Attribute bag to update in place.
     * @param string|UnitEnum $key Attribute key.
     * @param string $prefix Prefix to ensure (for example, `aria-`, `data-`, `on`).
     *
     * @throws InvalidArgumentException if normalized key is not a non-empty `string`.
     *
     * @phpstan-param mixed[] $attributes
     */
    public static function remove(array &$attributes, string|UnitEnum $key, string $prefix = ''): void
    {
        $normalizedKey = self::normalizeKey($key, $prefix);

        unset($attributes[$normalizedKey]);
    }

    /**
     * Sets a plain attribute key/value pair.
     *
     * Usage example:
     * ```php
     * $attributes = [];
     * \UIAwesome\Html\Helper\AttributeBag::set($attributes, 'disabled', true);
     * \UIAwesome\Html\Helper\AttributeBag::set($attributes, 'id', static fn () => 'submit');
     * \UIAwesome\Html\Helper\AttributeBag::set($attributes, 'label', 'Submit', 'aria-');
     * ```
     *
     * @param array $attributes Attribute bag to update in place.
     * @param string|UnitEnum $key Attribute key to normalize.
     * @param mixed $value Attribute value.
     * @param string $prefix Prefix to ensure (for example, `aria-`, `data-`, `on`).
     *
     * @throws InvalidArgumentException if key normalization fails.
     *
     * @phpstan-param mixed[] $attributes
     */
    public static function set(array &$attributes, string|UnitEnum $key, mixed $value, string $prefix = ''): void
    {
        $normalizedKey = self::normalizeKey($key, $prefix);

        if ($value instanceof Closure) {
            $value = $value();
        }

        if (is_bool($value) && self::shouldStoreBooleanAsString($normalizedKey)) {
            $value = $value ? 'true' : 'false';
        }

        $attributes[$normalizedKey] = $value;
    }

    /**
     * Sets multiple plain attributes.
     *
     * Usage example:
     * ```php
     * $attributes = [];
     * \UIAwesome\Html\Helper\AttributeBag::setMany(
     *     $attributes,
     *     [
     *         'disabled' => true,
     *         'id' => static fn () => 'submit',
     *     ],
     * );
     * \UIAwesome\Html\Helper\AttributeBag::setMany(
     *     $attributes,
     *     ['label' => 'Submit'],
     *     'aria-',
     * );
     * ```
     *
     * @param array $attributes Attribute bag to update in place.
     * @param array $values Values to set.
     * @param string $prefix Prefix to ensure (for example, `aria-`, `data-`, `on`).
     *
     * @throws InvalidArgumentException if any key normalization fails.
     *
     * @phpstan-param mixed[] $attributes
     * @phpstan-param mixed[] $values
     */
    public static function setMany(array &$attributes, array $values, string $prefix = ''): void
    {
        foreach ($values as $key => $value) {
            self::set($attributes, self::prepareManyKey($key), $value, $prefix);
        }
    }

    /**
     * Prepares an array key for `*Many()` methods.
     *
     * @param mixed $key Array key.
     *
     * @throws InvalidArgumentException when key is not a non-empty string.
     *
     * @return string Prepared key.
     */
    private static function prepareManyKey(mixed $key): string
    {
        if (is_string($key) && $key !== '') {
            return $key;
        }

        throw new InvalidArgumentException(
            Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage(),
        );
    }

    /**
     * Determines whether boolean values must be kept as literal strings.
     */
    private static function shouldStoreBooleanAsString(string $key): bool
    {
        return preg_match('/^(aria-|data-|data-ng-|ng-|on)/', $key) === 1;
    }
}
