<?php

declare(strict_types=1);

function dd(mixed $value)
{
    echo "<pre>";
    var_dump($value);
    echo "<pre>";
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value);
}

function redirectTo($path)
{
    header("Location: {$path}");
    http_response_code(302);
    exit;
}
