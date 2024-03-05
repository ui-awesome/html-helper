<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use function array_merge;
use function gettype;
use function implode;
use function in_array;
use function is_array;
use function json_encode;
use function rtrim;

/**
 * This class provides static methods for managing HTML tag attributes.
 */
final class Attributes
{
    /**
     * @var array list of tag attributes that should be specially handled when their values are of an array type.
     *
     * In particular, if the value of the `data` attribute is `['name' => 'xyz', 'age' => 13]`, two attributes will be
     * generated instead of one: `data-name="xyz" data-age="13"`.
     */
    private const DATA = ['aria', 'data', 'data-ng', 'ng'];

    /**
     * @var int the JSON encoding options used in {@see renderAttribute()} when rendering array values.
     */
    private const JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS |
         JSON_THROW_ON_ERROR;

    /**
     * @var array the preferred order of attributes in a tag. This mainly affects the order of the attributes that are
     * rendered by {@see render()}.
     *
     * @psalm-var string[]
     */
    private const ORDER = [
        'class',
        'id',
        'name',
        'type',
        'http-equiv',
        'value',
        'href',
        'src',
        'for',
        'title',
        'alt',
        'role',
        'tabindex',
        'srcset',
        'form',
        'action',
        'method',
        'selected',
        'checked',
        'readonly',
        'disabled',
        'multiple',
        'size',
        'maxlength',
        'width',
        'height',
        'rows',
        'cols',
        'alt',
        'title',
        'rel',
        'media',
    ];

    /**
     * Renders the HTML tag attributes.
     *
     * Attributes whose values are of boolean type will be treated as
     * [boolean attributes](http://www.w3.org/TR/html5/infrastructure.html#boolean-attributes).
     *
     * Attributes whose values are null will not be rendered.
     *
     * The values of attributes will be HTML-encoded using {@see encode()}.
     *
     * @param array $attributes Attributes to be rendered. The attribute values will be HTML-encoded using
     * {@see Encode}.
     *
     * `aria` and `data` attributes get special handling when they are set to an array value. In these cases, the array
     * will be "expanded" and a list of ARIA/data attributes will be rendered. For example,
     * `'aria' => ['role' => 'checkbox', 'value' => 'true']` would be rendered as
     * `aria-role="checkbox" aria-value="true"`.
     *
     * If a nested `data` value is set to an array, it will be JSON-encoded. For example,
     * `'data' => ['params' => ['id' => 1, 'name' => 'yii']]` would be rendered as
     * `data-params='{"id":1,"name":"yii"}'`.
     *
     * @return string The rendering result. If the attributes are not empty, they will be rendered into a string with a
     * leading space (so that it can be directly appended to the tag name in a tag). If there is no attribute, an
     * empty string will be returned.
     *
     * {@see addCssClass()}
     */
    public static function render(array $attributes): string
    {
        $html = '';
        $sorted = [];

        foreach (self::ORDER as $name) {
            if (isset($attributes[$name])) {
                /** @psalm-var string[] $sorted */
                $sorted[$name] = $attributes[$name];
            }
        }

        $attributes = array_merge($sorted, $attributes);

        /**
         * @var string $name
         * @var mixed $values
         */
        foreach ($attributes as $name => $values) {
            if ($name !== '' && $values !== '' && $values !== null) {
                $html .= self::renderAttributes($name, $values);
            }
        }

        return $html;
    }

    private static function renderAttribute(string $name, string $encodedValue = '', string $quote = '"'): string
    {
        if ($encodedValue === '') {
            return ' ' . $name;
        }

        return ' ' . $name . '=' . $quote . $encodedValue . $quote;
    }

    private static function renderAttributes(string $name, mixed $values): string
    {
        return match (gettype($values)) {
            'array' => self::renderArrayAttributes($name, $values),
            'boolean' => self::renderBooleanAttributes($name, $values),
            default => self::renderAttribute($name, Encode::value($values)),
        };
    }

    private static function renderArrayAttributes(string $name, array $values): string
    {
        $attributes = self::renderAttribute($name, json_encode($values, self::JSON_FLAGS), '\'');

        if (in_array($name, self::DATA, true)) {
            $attributes = self::renderDataAttributes($name, $values);
        }

        if ($name === 'class') {
            $attributes = self::renderClassAttributes($name, $values);
        }

        if ($name === 'style') {
            $attributes = self::renderStyleAttributes($name, $values);
        }

        return $attributes;
    }

    private static function renderBooleanAttributes(string $name, bool $value): string
    {
        return $value === true ? self::renderAttribute($name) : '';
    }

    private static function renderClassAttributes(string $name, array $values): string
    {
        /** @psalm-var string[] $values */
        return match ($values) {
            [] => '',
            default => " $name=\"" . Encode::content(implode(' ', $values)) . '"',
        };
    }

    private static function renderDataAttributes(string $name, array $values): string
    {
        $result = '';

        /** @psalm-var array<array-key, array|string|\Stringable|null> $values */
        foreach ($values as $n => $v) {
            $result .= match (is_array($v)) {
                true => self::renderAttribute($name . '-' . $n, json_encode($v, self::JSON_FLAGS), '\''),
                false => self::renderAttribute($name . '-' . $n, Encode::value($v)),
            };
        }

        return $result;
    }

    private static function renderStyleAttributes(string $name, array $values): string
    {
        $result = '';

        /** @psalm-var string[] $values */
        foreach ($values as $n => $v) {
            $result .= "$n: $v; ";
        }

        return $result === '' ? '' : " $name=\"" . Encode::content(rtrim($result)) . '"';
    }
}
