<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

class FlashMiddleware implements MiddlewareInterface
{
    public function __construct(private TemplateEngine $view)
    {
    }
    public function process(callable $next)
    {
        $this->view->addGlobalData('errors', $_SESSION['errors'] ?? []);

        unset($_SESSION['errors']);

        $this->view->addGlobalData('oldFormData', $_SESSION['oldFormData'] ?? []);
        unset($_SESSION['oldFormData']);
        $next();
    }
}
