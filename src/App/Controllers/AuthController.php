<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, UserService};

class AuthController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private UserService $userService,
    ) {
    }

    public function registerView()
    {
        echo  $this->view->render("register.php");
    }

    public function register()
    {
        $this->validatorService->validatorRegister($_POST);

        $this->userService->isEmailTaken($_POST['email']);

        $this->userService->create($_POST);

        return redirectTo('/');
    }

    public function loginView()
    {
        echo  $this->view->render("login.php");
    }

    public function login()
    {
        // $this->loginService->verifyLogin($_POST);
        $this->validatorService->validateLogin($_POST);

        $this->userService->login($_POST);

        return redirectTo('/');
    }

    public function logout()
    {
        $this->userService->logout();
        redirectTo('/login');
    }
}
