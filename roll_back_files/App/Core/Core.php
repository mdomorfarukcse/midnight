<?php

namespace Pemm\Core;

use Symfony\Component\HttpFoundation\Request;
use Pemm\Model\Setting;

abstract class Core
{
    /**
     * @var Container
     */
    public $container;

    /**
     * @var Database|null
     */
    public $database;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Setting
     */
    public $setting;

    /**
     * @var Language
     */
    public $language;

    public function __construct()
    {
        global $container;
        $this->container = $container;
        $this->database = $container->get('database');
        $this->request = $container->get('request');
        $this->setting = $container->get('setting');
        $this->language = $container->get('language');
    }
}
