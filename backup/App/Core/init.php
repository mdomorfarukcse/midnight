<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Pemm\Core\Container;
use Pemm\Core\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Core\Router;
use Pemm\Model\Setting;
use Pemm\Model\Currency;
use Pemm\Model\Customer;
use Pemm\Config;
use Pemm\Core\Language;

const ALERTS = [
	'error' => 'danger',
	'warning' => 'warning',
	'info' => 'primary',
	'success' => 'success'
];

$container = Container::getInstance();
$session = new Session();
$session->getFlashBag()->clear();
$container->set('session', $session);
$database = Database::getInstance();
$container->set('database', $database);
$request = Request::createFromGlobals();
$container->set('currency', new Currency());

if ($request->query->has('confirm_message')) {
	$session->getFlashBag()->add('info', $request->query->get('confirm_message'));
}

if ($request->request->has('lang')) {
	$session->set('language', $request->request->get('lang'));
	$request->setMethod('GET');
}

if ($request->request->has('currency')) {
	$session->set('currency', $request->request->get('currency'));
	$request->setMethod('GET');
}


$container->set('request', $request);
$container->set('config', new Config());
$container->set('setting', (new Setting())->find(1));


if (!$session->has('currency')) {
    $session->set('currency',  $container->get('setting')->getDefault_currency_method());
}

$language = new Language($container);
$container->set('language', $language);

define('PUBLIC_DIR', dirname(__DIR__, 2) . '/public/');
define('SITE_URL', $container->get('setting')->getSiteUrl());
define('SITE_NAME', $container->get('setting')->getSiteName());

date_default_timezone_set($container->get('setting')->getDefaultTimeZone());

new Router();

?>
