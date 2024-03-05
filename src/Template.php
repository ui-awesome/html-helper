<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use function count;
use function explode;
use function strtr;

/**
 * This class provides static methods for render template.
 */
final class Template
{
    public static function render(string $template, array $tokenValues): string
    {
        $result = '';
        $tokens = explode('\n', $template);

        foreach ($tokens as $key => $token) {
            $tokenValue = strtr($token, $tokenValues);

            if ($tokenValue !== '') {
                $result .= $tokenValue;
            }

            if ($result !== '' && $key < count($tokens) - 1) {
                $result = strtr($tokens[$key + 1], $tokenValues) !== '' ? $result . PHP_EOL : $result;
            }
        }

        return $result;
    }
}
