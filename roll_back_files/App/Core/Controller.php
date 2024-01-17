<?php

namespace Pemm\Core;

abstract class Controller extends Core
{
    /**
     * @var array
     */
    protected $route_params = [];
    public function __construct($route_params)
    {
        parent::__construct();
        $this->route_params = $route_params;
        $config = new \Pemm\Config();
        // $this->lisansimo_check($config->LICENSE_KEY, "https://lisans.proecufile.com/", 10);
    }
    public function lisansimo_check($license_key, $lisansimo_server, $time)
    {
        $_COOKIE =& $_COOKIE;
        $stime = time();
        if (!isset($_COOKIE["lisansimo"]) || $time < $stime - (int) $_COOKIE["lisansimo"]) {
            unset($_COOKIE["lisansimo"]);
            setcookie("lisansimo", $stime);
        }
        if ($time == 0 || !isset($_COOKIE["lisansimo"]) || $_COOKIE["lisansimo"] - $stime == 0) {
            $lisansimo_ch = curl_init();
            curl_setopt($lisansimo_ch, CURLOPT_URL, $lisansimo_server . "check");
            curl_setopt($lisansimo_ch, CURLOPT_POST, 1);
            curl_setopt($lisansimo_ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($lisansimo_ch, CURLOPT_POSTFIELDS, http_build_query(["license_key" => $license_key, "url" => (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], "server_ip" => $_SERVER["SERVER_ADDR"], "user_ip" => $_SERVER["REMOTE_ADDR"]]));
            curl_setopt($lisansimo_ch, CURLOPT_RETURNTRANSFER, true);
            $lisansimo_data = curl_exec($lisansimo_ch);
            if (curl_errno($lisansimo_ch) === 0) {
                $lisansimo_result = json_decode($lisansimo_data);
                curl_close($lisansimo_ch);
                if (!$lisansimo_result->valid) {
                    unset($_COOKIE["lisansimo"]);
                    setcookie("lisansimo", 0);
                    echo file_get_contents($lisansimo_server . "page/warning");
                    exit;
                }
            }
        }
    }
    public function __call($name, $args)
    {
        $method = $name . "Action";
        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method " . $method . " not found in controller " . get_class($this));
        }
    }
    protected function before()
    {
    }
    protected function after()
    {
    }
}

/*
namespace Pemm\Core;

class Controller
{
    public $container;
    public $request;
    public $database;
    public $setting;
    public $language;

    public function __construct( $container){
        global $container;
        $this->container = $container;
        $this->request = $this->container->get('request');
        $this->database = $container->get('database');
        $this->setting = $container->get('setting');
        $this->language = $container->get('language');
        $this->language = $container->get('language');
	    //echo "<pre>";
        //var_dump($this->container->get('request'));
        //echo "</pre>";
		//die();
    }

}
*/