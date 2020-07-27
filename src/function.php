<?php

namespace App;

function render(string $template, array $data = []): string
{
    $content = (function (string $template, array $data): string {
        extract($data, EXTR_SKIP);
        ob_start();
        require __DIR__ . "/../templates/{$template}.phtml";
        return ob_get_clean();
    })($template, $data);

    ob_start();
    require __DIR__ . "/../templates/layout.phtml";
    return ob_get_clean();
}
