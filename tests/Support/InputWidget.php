<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support;

use UIAwesome\Html\Interop\RenderInterface;

final class InputWidget implements RenderInterface
{
    public function __toString(): string
    {
        return $this->render();
    }

    public function render(): string
    {
        return '<input type="text" value="test" style="padding-left:20px" oinvalid="" onfocus="alert(\'XSS\')" />';
    }
}
