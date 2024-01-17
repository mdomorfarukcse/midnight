<?php

namespace Pemm\Views\Notification;

use Pemm\Model\Customer;
use Pemm\Model\CustomerVehicle;
use Pemm\Model\Helper;
use Pemm\Model\Order;
use Pemm\Model\Setting;

class CustomerVehicleMail
{
    public static function statusChange(CustomerVehicle $customerVehicle, $wmvmanual_data = array('status' => false))
    {
        global $container;

        /* @var Setting $setting */
        $setting = $container->get('setting');

        $content[] = '<!doctype html>
                        <html lang="en-US">
                        <head>
                            <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/>
                            <title>File Status Change : ' . $customerVehicle->getStatus() . '</title>
                            <meta name="description" content="Forgot Password">
                            <style type="text/css">
                                a:hover {
                                    text-decoration: underline !important;
                                }
                            </style>
                        </head>

                        <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #0e1726;" leftmargin="0">
                        <!--100% body table-->
                        <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#0e1726"
                               style="@import url(https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;">
                            <tr>
                                <td>
                                    <table style="background-color: #0e1726; max-width:670px;  margin:0 auto;" width="100%" border="0"
                                           align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="height:80px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;">
                                                <a href="' . $setting->getSiteUrl() . '" title="logo" target="_blank">
                                                <img width="128px" src="' . $setting->getSiteUrl() . $setting->getSiteEmailLogo(true) .'"
                                                     title="logo" alt="logo">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:20px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                                       style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                                    <tr>
                                                        <td style="height:40px;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:0 35px;">
                                                            <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:28px;font-family: \'Open Sans\', sans-serif;">File Status Change!</h1>
                                                            <span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                                            <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                                                Vehicle :  ' . (!empty($wmvmanual_data['wmv_vehicle_name']) ? ($wmvmanual_data['wmv_vehicle_name'] . ' ' . $wmvmanual_data['wmv_brand_name'] . ' ' . $wmvmanual_data['wmv_generation_name'] . ' ' . $wmvmanual_data['wmv_engine_name']) : $customerVehicle->vehicle->getFullName()) . ' <br>
                                                                Tuning Type :  ' . $customerVehicle->vehicleTuning->getName() . ' <br>
                                                                Status :  ' . ucfirst($customerVehicle->getStatus()) . '  <br>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height:40px;">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        <tr>
                                            <td style="height:20px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;">
                                                <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">
                                                    &copy; <strong>' . $setting->getSiteName() . '</strong></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:80px;">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        </body>
                        </html>
                        ';;


        return implode(' ', $content);

    }
}
