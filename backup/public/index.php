<?php
header('Set-Cookie: cross-site-cookie=bar; SameSite=None; Secure');
ini_set('session.cookie_path', "/; samesite=none; secure");

require dirname(__DIR__) . '/vendor/autoload.php';

set_error_handler('Pemm\Core\Error::errorHandler');
set_exception_handler('Pemm\Core\Error::exceptionHandler');
//date_default_timezone_set("Europe/Istanbul");

session_start();

include dirname(__DIR__) . '/App/Core/init.php';