<?php
include("../../App/Config.php");
use Pemm\Config;
$config = new Config;
include("inc/pdo.php");

function connect($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Origin: https://www.evc.de",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
    return $resp;

}

$database = SimplePDO::getInstance();

$database->query("SELECT * FROM `setting`");
$result = $database->single();

$evcApi = $result["evc_api"];
$evcUsername = $result["evc_username"];
$evcPass = $result["evc_pass"];

if(!isset($evcApi)||!isset($evcApi)||!isset($evcApi)) {
    die("API INFORMATION NOT FOUND");
}

$islem = $_GET["islem"];
$customer = $_GET["customer"];
if(!isset($islem)) { die("NO PARAMETER");}
if(!isset($customer)) { die("NO CUSTOMER PARAMETER");}
$url = "https://evc.de/services/api_resellercredits.asp?apiid=".trim($evcApi)."&username=".trim($evcUsername)."&password=".trim($evcPass)."&verb=";


switch ($islem) {
    case 'checkevccustomer':
    case 'evcNumberControl': // Evcde Bu Müşteri Numarası Varmı

        $url .= "checkevccustomer&customer=".$customer;
        $veri = connect($url);
        $bol = explode(":",trim($veri));
        header('Content-Type: application/json; charset=utf-8');
        if($bol[0]=="ok"||$bol[0]=="OK") {
            echo json_encode(["status" => "OK"]);
            die();
        }else{
            echo json_encode(["status" => "FAIL"]);
            die();
        }

        break;

    case 'checkcustomer':
    case 'evcResellerCustomerControl': // Resellerımda Bu Müşteri Numarası Varmı

    $url .= "checkcustomer&customer=".$customer;
    $veri = connect($url);
    $bol = explode(":",trim($veri));
    header('Content-Type: application/json; charset=utf-8');
    if($bol[0]=="ok"||$bol[0]=="OK") {
        echo json_encode(["status" => "OK"]);
        die();
    }else{
        echo json_encode(["status" => "FAIL"]);
        die();
    }

        break;

    case 'addcustomer':
    case 'evcAddCustomerReseller': // Resellara müşteri ekleme
    $url .= "addcustomer&customer=".$customer;
    $veri = connect($url);
    $bol = explode(":",trim($veri));
    header('Content-Type: application/json; charset=utf-8');
    if($bol[0]=="ok"||$bol[0]=="OK") {
        echo json_encode(["status" => "OK"]);
        die();
    }else{
        echo json_encode(["status" => "FAIL","message"=>$bol[1]]);
        die();
    }

        break;


    case 'getcustomeraccount':
    case 'checkBalance': // Müşteri Bakiye Sorgulama
        $url .= "getcustomeraccount&customer=".$customer;
        $veri = connect($url);
        $bol = explode(":",trim($veri));
        header('Content-Type: application/json; charset=utf-8');
        if($bol[0]=="ok"||$bol[0]=="OK") {
            echo json_encode(["status" => "OK","balance"=>trim($bol[1])]);
            die();
        }else{
            echo json_encode(["status" => "FAIL","message"=>$bol[1]]);
            die();
        }

        break;

    case 'setcustomeraccount':
    case 'updateBalance': // Müşteri Bakiye Güncelleme (Var olanı siler)
    $credits = $_GET["credits"];
    if(!isset($credits)) { die("NO CREDIT PARAMETER");}
    $url .= "setcustomeraccount&customer=".$customer."&credits=".$credits;
    $veri = connect($url);
    $bol = explode(":",trim($veri));
    header('Content-Type: application/json; charset=utf-8');
    if($bol[0]=="ok"||$bol[0]=="OK") {
        echo json_encode(["status" => "OK","balance"=>trim($bol[1])]);
        die();
    }else{
        echo json_encode(["status" => "FAIL","message"=>$bol[1]]);
        die();
    }

        break;

    case 'addcustomeraccount':
    case 'addBalance': // Müşteri Bakiye Ekleme (Var olanın üstüne ekler)
        $credits = $_GET["credits"];
        if(!isset($credits)) { die("NO CREDIT PARAMETER");}
        $url .= "addcustomeraccount&customer=".$customer."&credits=".$credits;
        $veri = connect($url);
        $bol = explode(":",trim($veri));
        header('Content-Type: application/json; charset=utf-8');
        if($bol[0]=="ok"||$bol[0]=="OK") {
            echo json_encode(["status" => "OK","balance"=>trim($bol[1])]);
            die();
        }else{
            echo json_encode(["status" => "FAIL","message"=>$bol[1]]);
            die();
        }

        break;
}

?>
