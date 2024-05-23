<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Execeptions\SessionException;
use Framework\Contracts\MiddlewareInterface;
use RuntimeException;

class SessionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException("Session already active");
        }


        if (headers_sent($filename, $line)) {
            throw new RuntimeException("Header already sent. Consider enabling output buffering. Data outputed from {$filename} - Line: {$line} ");
        }

        session_set_cookie_params([
            'secure' => $_ENV['APP_ENV'] === "production",
            'httponly' => true,
            'samesite' => 'lax'
        ]);

        session_start();
        $next();

        session_write_close();
    }
}
