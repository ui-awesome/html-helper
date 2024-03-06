<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use UIAwesome\Html\Helper\Attributes;

use function in_array;
use function strtolower;

/**
 * Provides common functionality for generate HTML code fragments programmatically.
 *
 * Concrete classes should extend this class to implement specific HTML elements and their generation logic.
 */
abstract class AbstractHTMLBuilder
{
    /**
     * @psalm-var string[]
     */
    private const INLINE_ELEMENTS = [
        'a',
        'abbr',
        'acronym',
        'audio',
        'b',
        'bdi',
        'bdo',
        'big',
        'br',
        'button',
        'canvas',
        'cite',
        'code',
        'data',
        'datalist',
        'del',
        'dfn',
        'em',
        'embed',
        'i',
        'iframe',
        'img',
        'input',
        'ins',
        'kbd',
        'label',
        'map',
        'mark',
        'meter',
        'noscript',
        'object',
        'option',
        'output',
        'picture',
        'progress',
        'q',
        'ruby',
        's',
        'samp',
        'script',
        'select',
        'slot',
        'small',
        'span',
        'strong',
        'sub',
        'sup',
        'svg',
        'template',
        'textarea',
        'time',
        'u',
        'td',
        'th',
        'tt',
        'var',
        'video',
        'wbr',
    ];

    /**
     * @psalm-var string[]
     */
    private const VOID_ELEMENT = [
        'area',
        'base',
        'br',
        'col',
        'command',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    ];

    /**
     * This method creates a new HTML begin tag with the specified tag name and attributes.
     *
     * @param string $tag The tag name.
     * @param array $attributes The tag attributes.
     *
     * @return string The begin tag.
     */
    public static function beginTag(string $tag, array $attributes = []): string
    {
        $helperAttributes = new Attributes();
        $tag = self::validateTag($tag);

        if (self::inlinedElements($tag)) {
            throw new \InvalidArgumentException('Inline elements cannot be used with begin/end syntax.');
        }

        return '<' . $tag . $helperAttributes->render($attributes) . '>';
    }

    /**
     * This method creates a new HTML tag with the specified tag name, content, and attributes.
     *
     * @param string $tag The tag name.
     * @param string $content The content of the tag.
     * @param array $attributes The attributes of the tag.
     *
     * @return string The tag.
     */
    public static function createTag(string $tag, string $content = '', array $attributes = []): string
    {
        $tag = self::validateTag($tag);
        $voidElement = "<$tag" . Attributes::render($attributes) . '>';

        if (self::voidElements($tag)) {
            return $voidElement;
        }

        if (self::inlinedElements($tag)) {
            return "$voidElement$content</$tag>";
        }

        $content = $content === '' ? '' : $content . PHP_EOL;

        return "$voidElement\n$content</$tag>";
    }

    /**
     * This method creates a new HTML end tag with the specified tag name.
     *
     * @param string $tag The tag name.
     *
     * @return string The closing tag.
     */
    public static function endTag(string $tag): string
    {
        if (self::inlinedElements($tag)) {
            throw new \InvalidArgumentException('Inline elements cannot be used with begin/end syntax.');
        }

        $tag = self::validateTag($tag);

        return "</$tag>";
    }

    /**
     * @return bool True if tag is inlined element.
     *
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Inline_elements
     */
    private static function inlinedElements(string $tag): bool
    {
        return in_array($tag, self::INLINE_ELEMENTS, true);
    }

    /**
     * @return bool True if tag is void element.
     *
     * @link http://www.w3.org/TR/html-markup/syntax.html#void-element
     */
    private static function voidElements(string $tag): bool
    {
        return in_array($tag, self::VOID_ELEMENT, true);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private static function validateTag(string $tag): string
    {
        $tag = strtolower($tag);

        if ($tag === '') {
            throw new \InvalidArgumentException('Tag name cannot be empty.');
        }

        return $tag;
    }
}
