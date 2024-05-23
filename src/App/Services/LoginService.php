<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{EmailRule};

class LoginService
{
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator;
        $this->validator->add('email', new EmailRule);
    }

    public function verifyLogin(array $formData)
    {
        $this->validator->validate($formData, [
            'email' => ['required', 'email'],
            'password' => ['required', 'password']
        ]);
    }
}
