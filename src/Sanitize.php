<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Interop\RenderInterface;
use voku\helper\AntiXSS;

/**
 * This class provides static methods for sanitizing HTML content to prevent XSS attacks.
 */
final class Sanitize
{
    /**
     * @var array<string>
     */
    private static array $removeEvilAttributes = [
        'form',
        'formaction',
        'style',
    ];
    /**
     * @var array<string>
     */
    private static array $removeEvilHtmlTags = [
        'button',
        'form',
        'input',
        'select',
        'svg',
        'textarea',
    ];

    /**
     * Initialize the class with custom configuration.
     *
     * @psalm-param array<string> $removeEvilAttributes
     * @psalm-param array<string> $removeEvilHtmlTags
     */
    public static function initialize(array $removeEvilAttributes = [], array $removeEvilHtmlTags = []): void
    {
        self::$removeEvilAttributes = $removeEvilAttributes;
        self::$removeEvilHtmlTags = $removeEvilHtmlTags;
    }

    /**
     * Sanitizes HTML content to prevent XSS attacks.
     *
     * @param RenderInterface|string ...$values The HTML content to sanitize.
     *
     * @return string The sanitized HTML content.
     */
    public static function html(string|RenderInterface ...$values): string
    {
        $cleanHtml = '';

        foreach ($values as $value) {
            if ($value instanceof RenderInterface) {
                $value = $value->render();
            }

            /** @psalm-var string|string[] $cleanValue */
            $cleanValue = self::cleanXSS($value);
            $cleanValue = is_array($cleanValue) ? implode('', $cleanValue) : $cleanValue;

            $cleanHtml .= $cleanValue;
        }

        return $cleanHtml;
    }

    private static function cleanXSS(string $content): string|array
    {
        $antiXss = new AntiXSS();

        $antiXss->removeEvilAttributes(self::$removeEvilAttributes);
        $antiXss->removeEvilHtmlTags(self::$removeEvilHtmlTags);

        return $antiXss->xss_clean($content);
    }
}
