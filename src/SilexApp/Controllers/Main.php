<?php

namespace SilexApp\Controllers;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Response;

class Main
{
    public static function addRoutes(ControllerCollection $routing)
    {
        $routing->get('/', array(new self(), 'main'))->bind('main');
    }

    public function main(Application $app)
    {
        $api_response = sprintf('%s', 'OK');
        return new Response($api_response);
    }
} 