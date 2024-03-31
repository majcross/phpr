<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Exceptions\ValidationException;
use Framework\Contracts\MiddlewareInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        try {
            $next();
        } catch (ValidationException $e) {
            $oldFormData = $_POST;
            $excludedFields = ['password', 'confirmPassword'];
            $formattedData = array_diff_key(
                $oldFormData,
                array_flip($excludedFields)
            );
            $_SESSION['errors'] = $e->errors;
            // To receive the form data
            $_SESSION['oldFormData'] = $formattedData;
            // display errors
            // dd($e->errors);
            $referer = $_SERVER['HTTP_REFERER'];
            redirectTo($referer);
        }
    }
}
