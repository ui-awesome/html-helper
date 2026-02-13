<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use Closure;
use InvalidArgumentException;
use Stringable;
use UIAwesome\Html\Helper\{Attributes, Enum};
use UIAwesome\Html\Helper\Exception\Message;
use UnitEnum;

use function is_bool;
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

        return $attributes[$normalizedKey] ?? $default;
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
     * Normalizes and sets an attribute key/value pair.
     *
     * Resolves closure values, optionally converts boolean values to `'true'`/`'false'`, and removes the key when the
     * resolved value is `null`.
     *
     * Usage example:
     * ```php
     * $attributes = [];
     * \UIAwesome\Html\Helper\AttributeBag::set($attributes, 'disabled', true, '', true);
     * \UIAwesome\Html\Helper\AttributeBag::set($attributes, 'id', static fn () => 'submit');
     * ```
     *
     * @param array $attributes Attribute bag to update in place.
     * @param mixed $key Attribute key to normalize.
     * @param bool|Closure|float|int|string|Stringable|UnitEnum|null $value Attribute value.
     * @param string $prefix Prefix applied via {@see Attributes::normalizeKey()}.
     * @param bool $boolToString Whether boolean values should be cast to `'true'`/`'false'`.
     *
     * @throws InvalidArgumentException if key normalization fails.
     *
     * @phpstan-param mixed[] $attributes
     * @phpstan-param scalar|Stringable|UnitEnum|Closure(): mixed $value
     */
    public static function set(
        array &$attributes,
        mixed $key,
        bool|float|int|string|Closure|Stringable|UnitEnum|null $value,
        string $prefix = '',
        bool $boolToString = false,
    ): void {
        $normalizedKey = Attributes::normalizeKey($key, $prefix);
        $resolvedValue = $value instanceof Closure ? $value() : $value;

        if ($boolToString && is_bool($resolvedValue)) {
            $resolvedValue = $resolvedValue ? 'true' : 'false';
        }

        if ($resolvedValue === null) {
            unset($attributes[$normalizedKey]);

            return;
        }

        $attributes[$normalizedKey] = $resolvedValue;
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
}
