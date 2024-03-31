<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class HomeController
{


    public function __construct(private TemplateEngine $view)
    {
        // $this->view = new TemplateEngine(Paths::VIEW);
    }
    public function home()
    {
        // dd($this->view);
        echo  $this->view->render("index.php");
        //  [
        // 'title' => 'The Title'
        // ]);
    }
}
