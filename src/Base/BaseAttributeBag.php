<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use InvalidArgumentException;
use UIAwesome\Html\Helper\Enum;
use UIAwesome\Html\Helper\Exception\Message;
use UnitEnum;

use function array_key_exists;
use function is_string;

/**
 * Provides reusable operations for associative HTML attribute bags.
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseAttributeBag
{
    /**
     * Adds or removes an attribute in the given attribute bag.
     *
     * If the provided value is `null`, the normalized key is removed from the bag.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\AttributeBag::add($attributes, 'disabled', true);
     * \UIAwesome\Html\Helper\AttributeBag::add($attributes, 'id', null);
     * ```
     *
     * @param array $attributes Attribute bag to update in place.
     * @param string|UnitEnum $key Attribute key.
     * @param mixed $value Attribute value.
     *
     * @throws InvalidArgumentException if normalized key is not a non-empty `string`.
     *
     * @phpstan-param mixed[] $attributes
     */
    public static function add(array &$attributes, string|UnitEnum $key, mixed $value): void
    {
        $normalizedKey = self::normalizeKey($key);

        if ($value === null) {
            unset($attributes[$normalizedKey]);

            return;
        }

        $attributes[$normalizedKey] = $value;
    }

    /**
     * Returns an attribute value by key.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\AttributeBag::get($attributes, 'id');
     * \UIAwesome\Html\Helper\AttributeBag::get($attributes, 'type', 'button');
     * ```
     *
     * @param array $attributes Attribute bag.
     * @param string|UnitEnum $key Attribute key.
     * @param mixed $default Default value when key is not present.
     *
     * @throws InvalidArgumentException if normalized key is not a non-empty `string`.
     *
     * @return mixed Attribute value or default.
     *
     * @phpstan-param mixed[] $attributes
     */
    public static function get(array $attributes, string|UnitEnum $key, mixed $default = null): mixed
    {
        $normalizedKey = self::normalizeKey($key);

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
     * Removes an attribute from the bag.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\AttributeBag::remove($attributes, 'disabled');
     * ```
     *
     * @param array $attributes Attribute bag to update in place.
     * @param string|UnitEnum $key Attribute key.
     *
     * @throws InvalidArgumentException if normalized key is not a non-empty `string`.
     *
     * @phpstan-param mixed[] $attributes
     */
    public static function remove(array &$attributes, string|UnitEnum $key): void
    {
        $normalizedKey = self::normalizeKey($key);

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
     * ```
     *
     * @param array $attributes Attribute bag to update in place.
     * @param string|UnitEnum $key Attribute key to normalize.
     * @param mixed $value Attribute value.
     *
     * @throws InvalidArgumentException if key normalization fails.
     *
     * @phpstan-param mixed[] $attributes
     */
    public static function set(array &$attributes, string|UnitEnum $key, mixed $value): void
    {
        $normalizedKey = self::normalizeKey($key);

        if ($value === null) {
            unset($attributes[$normalizedKey]);

            return;
        }

        $attributes[$normalizedKey] = $value;
    }

    /**
     * Sets multiple plain attributes.
     *
     * @param array $attributes Attribute bag to update in place.
     * @param array $values Values to set.
     *
     * @throws InvalidArgumentException if any key normalization fails.
     *
     * @phpstan-param mixed[] $attributes
     * @phpstan-param mixed[] $values
     */
    public static function setMany(array &$attributes, array $values): void
    {
        foreach ($values as $key => $value) {
            self::set($attributes, self::prepareManyKey($key), $value);
        }
    }

    /**
     * Normalizes an attribute bag key using enum-aware normalization.
     *
     * @param string|UnitEnum $key Attribute key.
     *
     * @throws InvalidArgumentException if normalized key is not a non-empty `string`.
     *
     * @return string Normalized key.
     */
    private static function normalizeKey(string|UnitEnum $key): string
    {
        $normalizedKey = Enum::normalizeValue($key);

        if ($normalizedKey === '' || is_string($normalizedKey) === false) {
            throw new InvalidArgumentException(
                Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage(),
            );
        }

        return $normalizedKey;
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
        if (is_string($key)) {
            return $key;
        }

        throw new InvalidArgumentException(
            Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage(),
        );
    }

}
