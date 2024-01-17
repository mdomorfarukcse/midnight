<?php

namespace Pemm\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Setting;

class View extends Core
{
    /**
     * @param string $view
     * @param array $args
     * @return void
     */
    public static function render($directory, $view, $args = [])
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        extract($args, EXTR_SKIP);

        switch ($directory) {
            case 'admin':
                $file = dirname(__DIR__) . '/Views/' . $directory . '/' . $view .'.php';
                break;
            case 'customer':
                $file = dirname(__DIR__, 2) . '/public/' . $view .'.php';
                break;
        }


        global $container;

        /* @var Session $session */
        $session = $container->get('session');
        /* @var Request $request */
        $request = $container->get('request');
        /* @var Setting $setting */
        $setting = $container->get('setting');

        global $session;
        global $setting;

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * @param string $view
     * @param array $args
     * @return string
     */
    public static function renderTemplate($directory, $view, $args = [])
    {

    }
}
