<?php

namespace Pemm;

use Pemm\Core\Router;
use Pemm\Model\Administrator;
use Pemm\Model\Customer;
use Pemm\Model\User;

use Symfony\Component\HttpFoundation\RedirectResponse;

class Routing
{
    public function customer(Router $router)
    {
        $router->add('/test', ['controller' => 'Test', 'action' => 'webmavie_test']);
        // $router->add('/test/webmavie_test', ['controller' => 'Test', 'action' => 'webmavie_test']);
        
        $router->add('/mapping', ['controller' => 'Mapping', 'action' => 'index']);

        $router->add('/panel/register', ['controller' => 'Customer', 'action' => 'registerIndex']);
        $router->add('/panel/login', ['controller' => 'Customer', 'action' => 'loginIndex']);
        $router->add('/panel/forgot-password', ['controller' => 'Customer', 'action' => 'forgotPasswordIndex']);
        $router->add('/panel/reset-password', ['controller' => 'Customer', 'action' => 'resetPasswordIndex']);
        $router->add('/panel/account-activation', ['controller' => 'Customer', 'action' => 'accountActivation']);

        $router->add('/iyzipay/callback', ['controller' => 'Payment', 'action' => 'iyzipayCallback']);
        $router->add('/btcpayserver/callback', ['controller' => 'Payment', 'action' => 'btcpayserverCallback']);
        $router->add('/mollie-callback', ['controller' => 'Payment', 'action' => 'mollieCalback']);
        $router->add('/stripe-charge', ['controller' => 'Payment', 'action' => 'StripeCharge']);
        $router->add('/stripe-callback', ['controller' => 'Payment', 'action' => 'stripeCallback']);
        $router->add('/paypal/callback', ['controller' => 'Payment', 'action' => 'paypalCallback']);
        $router->add('/paypal-callback', ['controller' => 'Payment', 'action' => 'paypalCallback']);
        $router->add('/paypal-success', ['controller' => 'Gecici', 'action' => 'success']);
        $router->add('/paypal-cancel', ['controller' => 'Gecici', 'action' => 'cancel']);
        $router->add('/mollie-cancel', ['controller' => 'Gecici', 'action' => 'cancel']);
        $router->add('/mollie-info', ['controller' => 'Gecici', 'action' => 'info']);

        if ((new Customer())->check()) {
            $router->add('/panel/ticket', ['controller' => 'Support', 'action' => 'detail']);
            $router->add('/panel/ticket/{id}/close', ['controller' => 'Support', 'action' => 'close']);
            $router->add('/panel', ['controller' => 'Home', 'action' => 'index']);
            $router->add('/panel/', ['controller' => 'Home', 'action' => 'index']);
            $router->add('/panel/buy-credit', ['controller' => 'Credit', 'action' => 'list']);
            $router->add('/panel/buy-evc-credit', ['controller' => 'CreditEvc', 'action' => 'list']);
            $router->add('/panel/file-upload', ['controller' => 'Customer', 'action' => 'fileUpload']);
            $router->add('/panel/price-evc-list', ['controller' => 'PriceEvc', 'action' => 'list']);
            $router->add('/panel/price-list', ['controller' => 'Price', 'action' => 'list']);
            $router->add('/panel/credit-reports', ['controller' => 'Order', 'action' => 'list']);
            $router->add('/panel/my-files', ['controller' => 'CustomerVehicle', 'action' => 'list']);
            $router->add('/panel/my-files/detail/{id}', ['controller' => 'CustomerVehicle', 'action' => 'detail']);
            $router->add('/panel/auto-tuner', ['controller' => 'Vehicle', 'action' => 'list']);
            $router->add('/panel/bosch-codes', ['controller' => 'BoschCodes', 'action' => 'list']);
            $router->add('/panel/p-codes', ['controller' => 'PCodes', 'action' => 'list']);
            $router->add('/panel/support', ['controller' => 'Support', 'action' => 'index']);
            $router->add('/panel/logout', ['controller' => 'Customer', 'action' => 'logout']);
            $router->add('/panel/my-cart', ['controller' => 'Cart', 'action' => 'index']);
            $router->add('/panel/my-cart-evc', ['controller' => 'CartEvc', 'action' => 'index']);
            $router->add('/panel/checkout', ['controller' => 'Payment', 'action' => 'checkout']);
            $router->add('/panel/evc-checkout', ['controller' => 'Payment', 'action' => 'checkoutEvc']);
            $router->add('/panel/file/download', ['controller' => 'File', 'action' => 'download']);
            $router->add('/panel/file/support/{id}/download', ['controller' => 'File', 'action' => 'supportFileDownload']);
            $router->add('/panel/invoice/detail/{id}', ['controller' => 'Invoice', 'action' => 'detail']);
            $router->add('/panel/imprint', ['controller' => 'Gecici', 'action' => 'view']);
            $router->add('/panel/terms-and-conditions', ['controller' => 'Gecici', 'action' => 'view']);
            $router->add('/panel/privacy-policy', ['controller' => 'Gecici', 'action' => 'view']);
            $router->add('/panel/return-policy', ['controller' => 'Gecici', 'action' => 'view']);
            $router->add('/panel/delivery-information', ['controller' => 'Gecici', 'action' => 'view']);
            $router->add('/panel/about-us', ['controller' => 'Gecici', 'action' => 'view']);
     	    $router->add('/panel/contact-us', ['controller' => 'Gecici', 'action' => 'view']);
            $router->add('/panel/profil2', ['controller' => 'Gecici', 'action' => 'view']);
            $router->add('/panel/my-profile', ['controller' => 'Gecici', 'action' => 'view']);
            $router->add('/panel/edit-profile', ['controller' => 'Gecici', 'action' => 'view']);
            $router->add('/panel/customer-vehicle/{id}/pay', ['controller' => 'CustomerVehicle', 'action' => 'pay']);
        }

        /*
       $router->add('/security/create/code', ['controller' => 'Security', 'action' => 'createCode']);
       */
    }

    public function admin(Router $router)
    {

        global $container;

        $router->add('/admin/login', ['controller' => 'Admin\User', 'action' => 'loginIndex']);

        if ((new User())->check()) {

            $router->add('/admin/ticket', ['controller' => 'Admin\Support', 'action' => 'detail']);
            $router->add('/admin/ticket/{id}/close', ['controller' => 'Admin\Support', 'action' => 'close']);

            $router->add('/admin', ['controller' => 'Admin\Dashboard', 'action' => 'index']);
            $router->add('/admin/', ['controller' => 'Admin\Dashboard', 'action' => 'index']);

            $router->add('/admin/file/download', ['controller' => 'Admin\File', 'action' => 'download']);
            $router->add('/admin/file/support/{id}/download', ['controller' => 'Admin\File', 'action' => 'supportFileDownload']);

            $router->add('/admin/customer/list', ['controller' => 'Admin\Customer', 'action' => 'list']);
            $router->add('/admin/customer/detail/{id}', ['controller' => 'Admin\Customer', 'action' => 'form']);
            $router->add('/admin/customer/new', ['controller' => 'Admin\Customer', 'action' => 'form']);

            $router->add('/admin/customer-group/list', ['controller' => 'Admin\CustomerGroup', 'action' => 'list']);
            $router->add('/admin/customer-group/detail/{id}', ['controller' => 'Admin\CustomerGroup', 'action' => 'form']);
            $router->add('/admin/customer-group/new', ['controller' => 'Admin\CustomerGroup', 'action' => 'form']);


            $router->add('/admin/sms-provider/list', ['controller' => 'Admin\SmsProvider', 'action' => 'list']);
            $router->add('/admin/sms-provider/detail/{id}', ['controller' => 'Admin\SmsProvider', 'action' => 'form']);
            $router->add('/admin/sms-provider/new', ['controller' => 'Admin\SmsProvider', 'action' => 'form']);


            $router->add('/admin/customer/vehicle/list', ['controller' => 'Admin\CustomerVehicle', 'action' => 'list']);
            $router->add('/admin/customer/vehicle/detail/{id}', ['controller' => 'Admin\CustomerVehicle', 'action' => 'form']);
            $router->add('/admin/customer/vehicle/delete/{id}', ['controller' => 'Admin\CustomerVehicle', 'action' => 'delete']);
            $router->add('/admin/customer/vehicle/{id}/change-credit', ['controller' => 'Admin\CustomerVehicle', 'action' => 'changeCredit']);

            $router->add('/admin/customer/order/list', ['controller' => 'Admin\Order', 'action' => 'list']);
            $router->add('/admin/order/detail/{id}', ['controller' => 'Admin\Order', 'action' => 'form']);

            $router->add('/admin/customer/invoice/list', ['controller' => 'Admin\Invoice', 'action' => 'list']);
            $router->add('/admin/invoice/detail/{id}', ['controller' => 'Admin\Invoice', 'action' => 'form']);

            $router->add('/admin/vehicle/brand/list', ['controller' => 'Admin\Vehicle', 'action' => 'brandList']);
            $router->add('/admin/vehicle/brand/detail/{id}', ['controller' => 'Admin\Vehicle', 'action' => 'brandForm']);
            $router->add('/admin/vehicle/brand/new', ['controller' => 'Admin\Vehicle', 'action' => 'brandForm']);

            $router->add('/admin/vehicle/engine/list', ['controller' => 'Admin\Vehicle', 'action' => 'engineList']);
            $router->add('/admin/vehicle/engine/detail/{id}', ['controller' => 'Admin\Vehicle', 'action' => 'engineForm']);
            $router->add('/admin/vehicle/engine/new', ['controller' => 'Admin\Vehicle', 'action' => 'engineForm']);


            $router->add('/admin/vehicle/category/list', ['controller' => 'Admin\Vehicle', 'action' => 'categoryList']);
            $router->add('/admin/vehicle/category/detail/{id}', ['controller' => 'Admin\Vehicle', 'action' => 'categoryForm']);
            $router->add('/admin/vehicle/category/new', ['controller' => 'Admin\Vehicle', 'action' => 'categoryForm']);

            $router->add('/admin/vehicle/model/list', ['controller' => 'Admin\Vehicle', 'action' => 'modelList']);
            $router->add('/admin/vehicle/model/detail/{id}', ['controller' => 'Admin\Vehicle', 'action' => 'modelForm']);
            $router->add('/admin/vehicle/model/new', ['controller' => 'Admin\Vehicle', 'action' => 'modelForm']);

            $router->add('/admin/vehicle/years/list', ['controller' => 'Admin\Vehicle', 'action' => 'yearsList']);
            $router->add('/admin/vehicle/years/detail/{id}', ['controller' => 'Admin\Vehicle', 'action' => 'yearsForm']);
            $router->add('/admin/vehicle/years/new', ['controller' => 'Admin\Vehicle', 'action' => 'yearsForm']);
 
            //$router->add('/admin/vehicle/engine/new', ['controller' => 'Admin\Vehicle', 'action' => 'brandForm']);

            $router->add('/admin/vehicle/list', ['controller' => 'Admin\Vehicle', 'action' => 'list']);
            $router->add('/admin/vehicle/detail/{id}', ['controller' => 'Admin\Vehicle', 'action' => 'form']);
            $router->add('/admin/vehicle/new', ['controller' => 'Admin\Vehicle', 'action' => 'new']);

            $router->add('/admin/vehicle/tuning/list', ['controller' => 'Admin\Vehicle', 'action' => 'tuningList']);
            $router->add('/admin/vehicle/tuning/detail/{id}', ['controller' => 'Admin\Vehicle', 'action' => 'tuningForm']);
            $router->add('/admin/vehicle/tuning/new', ['controller' => 'Admin\Vehicle', 'action' => 'tuningForm']);

            $router->add('/admin/vehicle/tuning/option/list', ['controller' => 'Admin\Vehicle', 'action' => 'tuningOptionList']);
            $router->add('/admin/vehicle/tuning/option/detail/{id}', ['controller' => 'Admin\Vehicle', 'action' => 'tuningOptionForm']);
            $router->add('/admin/vehicle/tuning/option/new', ['controller' => 'Admin\Vehicle', 'action' => 'tuningOptionForm']);

            $router->add('/admin/vehicle/read-method/list', ['controller' => 'Admin\Vehicle', 'action' => 'readMethodList']);
            $router->add('/admin/vehicle/read-method/detail/{id}', ['controller' => 'Admin\Vehicle', 'action' => 'readMethodForm']);
            $router->add('/admin/vehicle/read-method/new', ['controller' => 'Admin\Vehicle', 'action' => 'readMethodForm']);

            $router->add('/admin/support', ['controller' => 'Admin\Support', 'action' => 'index']);

            $router->add('/admin/tuning/list', ['controller' => 'Admin\Tuning', 'action' => 'list']);
            $router->add('/admin/tuning/detail/{id}', ['controller' => 'Admin\Tuning', 'action' => 'form']);
            $router->add('/admin/tuning/new', ['controller' => 'Admin\Tuning', 'action' => 'form']);

            $router->add('/admin/tuning/option/list', ['controller' => 'Admin\Tuning', 'action' => 'optionList']);
            $router->add('/admin/tuning/option/detail/{id}', ['controller' => 'Admin\Tuning', 'action' => 'optionForm']);
            $router->add('/admin/tuning/option/new', ['controller' => 'Admin\Tuning', 'action' => 'optionForm']);

            $router->add('/admin/additional-option/list', ['controller' => 'Admin\AdditionalOption', 'action' => 'list']);
            $router->add('/admin/additional-option/detail/{id}', ['controller' => 'Admin\AdditionalOption', 'action' => 'form']);
            $router->add('/admin/additional-option/new', ['controller' => 'Admin\AdditionalOption', 'action' => 'form']);

            $router->add('/admin/read-methods/list', ['controller' => 'Admin\ReadMethod', 'action' => 'list']);
            $router->add('/admin/read-method/detail/{id}', ['controller' => 'Admin\ReadMethod', 'action' => 'form']);
            $router->add('/admin/read-method/new', ['controller' => 'Admin\ReadMethod', 'action' => 'form']);

            $router->add('/admin/bosch-codes/list', ['controller' => 'Admin\BoschCodes', 'action' => 'list']);
            $router->add('/admin/bosch-codes/detail/{id}', ['controller' => 'Admin\BoschCodes', 'action' => 'form']);
            $router->add('/admin/bosch-codes/new', ['controller' => 'Admin\BoschCodes', 'action' => 'form']);

            $router->add('/admin/p-codes/list', ['controller' => 'Admin\PCodes', 'action' => 'list']);
            $router->add('/admin/p-codes/detail/{id}', ['controller' => 'Admin\PCodes', 'action' => 'form']);
            $router->add('/admin/p-codes/new', ['controller' => 'Admin\PCodes', 'action' => 'form']);

            $router->add('/admin/product/list', ['controller' => 'Admin\Product', 'action' => 'list']);
            $router->add('/admin/product/detail/{id}', ['controller' => 'Admin\Product', 'action' => 'form']);
            $router->add('/admin/product/new', ['controller' => 'Admin\Product', 'action' => 'form']);

            $router->add('/admin/product-evc/list', ['controller' => 'Admin\ProductEvc', 'action' => 'list']);
            $router->add('/admin/product-evc/detail/{id}', ['controller' => 'Admin\ProductEvc', 'action' => 'form']);
            $router->add('/admin/product-evc/new', ['controller' => 'Admin\ProductEvc', 'action' => 'form']);

            $router->add('/admin/currency/list', ['controller' => 'Admin\Currency', 'action' => 'list']);
            $router->add('/admin/currency/detail/{id}', ['controller' => 'Admin\Currency', 'action' => 'form']);
            $router->add('/admin/currency/new', ['controller' => 'Admin\Currency', 'action' => 'form']);

            $router->add('/admin/currency/exchange-rate/list', ['controller' => 'Admin\ExchangeRate', 'action' => 'list']);
            $router->add('/admin/exchange-rate/detail/{id}', ['controller' => 'Admin\ExchangeRate', 'action' => 'form']);
            $router->add('/admin/exchange-rate/new', ['controller' => 'Admin\ExchangeRate', 'action' => 'form']);

            $router->add('/admin/country/list', ['controller' => 'Admin\Country', 'action' => 'list']);
            $router->add('/admin/country/detail/{id}', ['controller' => 'Admin\Country', 'action' => 'form']);
            $router->add('/admin/country/new', ['controller' => 'Admin\Country', 'action' => 'form']);

            $router->add('/admin/country/city/detail/{id}', ['controller' => 'Admin\Country', 'action' => 'cityForm']);
            $router->add('/admin/country/city/new', ['controller' => 'Admin\Country', 'action' => 'cityForm']);

            $router->add('/admin/user/list', ['controller' => 'Admin\User', 'action' => 'list']);
            $router->add('/admin/user/detail/{id}', ['controller' => 'Admin\User', 'action' => 'form']);
            $router->add('/admin/user/new', ['controller' => 'Admin\User', 'action' => 'form']);

            $router->add('/admin/setting/general', ['controller' => 'Admin\Setting', 'action' => 'general']);
            $router->add('/admin/setting/logo', ['controller' => 'Admin\Setting', 'action' => 'logo']);
            $router->add('/admin/setting/payment', ['controller' => 'Admin\Setting', 'action' => 'payment']);
            $router->add('/admin/setting/evc', ['controller' => 'Admin\Setting', 'action' => 'evc']);
            $router->add('/admin/setting/sms', ['controller' => 'Admin\Setting', 'action' => 'sms']);
            $router->add('/admin/setting/google', ['controller' => 'Admin\Setting', 'action' => 'google']);
            $router->add('/admin/setting/working', ['controller' => 'Admin\Setting', 'action' => 'working']);
            $router->add('/admin/setting/mail', ['controller' => 'Admin\Setting', 'action' => 'mail']);
            $router->add('/admin/setting/policies', ['controller' => 'Admin\Setting', 'action' => 'policies']);

            $router->add('/admin/reports', ['controller' => 'Admin\Reports', 'action' => 'general']);

            $router->add('/admin/logout', ['controller' => 'Admin\User', 'action' => 'logout']);

            $router->add('/admin/invoice/detail/{id}', ['controller' => 'Admin\Invoice', 'action' => 'detail']);
        }
    }

    public function ajax(Router $router, $type = 'customer')
    {
        if ($type == 'customer' && (new Customer())->check()) {

            $router->add('/ajax/category/{id}/get-sub-categories-for-select', ['controller' => 'Category', 'action' => 'ajaxGetSubCategories']);
            $router->add('/ajax/vehicle/{id}/get-ecu-for-select', ['controller' => 'Vehicle', 'action' => 'ajaxGetEcuByEngine']);
            $router->add('/ajax/vehicle/{id}/get-vehicle-by-id-with-html-for-home', ['controller' => 'Vehicle', 'action' => 'ajaxGetVehicleByIdWithHtmlForHome']);
            $router->add('/ajax/add-to-cart', ['controller' => 'Cart', 'action' => 'ajaxAddToCart']);
            $router->add('/ajax/delete-cart', ['controller' => 'Cart', 'action' => 'ajaxDeleteCart']);
            $router->add('/ajax/add-to-cart-evc', ['controller' => 'CartEvc', 'action' => 'ajaxAddToCart']);
            $router->add('/ajax/delete-cart-evc', ['controller' => 'CartEvc', 'action' => 'ajaxDeleteCart']);
            $router->add('/ajax/create-customer-vehicle', ['controller' => 'Customer', 'action' => 'ajaxCreateVehicle']);
            $router->add('/ajax/customer-vehicle/list-for-datatable', ['controller' => 'CustomerVehicle', 'action' => 'ajaxListForDataTable']);
            $router->add('/ajax/customer-vehicle/detail', ['controller' => 'CustomerVehicle', 'action' => 'getVehicleDetail']);
            $router->add('/ajax/vehicle/list-for-datatable', ['controller' => 'Vehicle', 'action' => 'ajaxListForDataTable']);
            $router->add('/ajax/bosch-codes/list-for-datatable', ['controller' => 'BoschCodes', 'action' => 'ajaxListForDataTable']);
            $router->add('/ajax/p-codes/list-for-datatable', ['controller' => 'PCodes', 'action' => 'ajaxListForDataTable']);
            $router->add('/ajax/customer/support/detail/{id}', ['controller' => 'Support', 'action' => 'ajaxSupportDetail']);
            $router->add('/ajax/customer/support/send-message/{id}', ['controller' => 'Support', 'action' => 'ajaxSendMessage']);
            $router->add('/ajax/payment/make', ['controller' => 'Payment', 'action' => 'ajaxMakePayment']);
            $router->add('/ajax/city-list', ['controller' => 'Country', 'action' => 'ajaxCityList']);

        }

        if ($type == 'admin' && (new User())->check()) {

            $router->add('/ajax/admin/category/{id}/get-sub-categories-for-select', ['controller' => 'Admin\Category', 'action' => 'ajaxGetSubCategories']);
            $router->add('/ajax/admin/category/list-for-datatable', ['controller' => 'Admin\Category', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/model/list-for-datatable', ['controller' => 'Admin\Category', 'action' => 'ajaxListModelsForDatatable']);
            $router->add('/ajax/admin/years/list-for-datatable', ['controller' => 'Admin\Category', 'action' => 'ajaxListYearsForDatatable']);
            $router->add('/ajax/admin/engine/list-for-datatable', ['controller' => 'Admin\Category', 'action' => 'ajaxListEngineForDatatable']);
            $router->add('/ajax/admin/vehicle/{id}/get-ecu-for-select', ['controller' => 'Vehicle', 'action' => 'ajaxGetEcuByEngine']);
            $router->add('/ajax/admin/vehicle/{id}/get-vehicle-by-id-with-html-for-home', ['controller' => 'Vehicle', 'action' => 'ajaxGetVehicleByIdWithHtmlForHome']);

            $router->add('/ajax/admin/customer/list-for-datatable', ['controller' => 'Admin\Customer', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/customer/vehicle/list-for-datatable', ['controller' => 'Admin\CustomerVehicle', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/customer/vehicle/list-for-customer', ['controller' => 'Admin\CustomerVehicle', 'action' => 'ajaxGetVehicles']);

            $router->add('/ajax/admin/customer/order/list-for-datatable', ['controller' => 'Admin\Order', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/customer/invoice/list-for-datatable', ['controller' => 'Admin\Invoice', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/customer/group-list-for-datatable', ['controller' => 'Admin\CustomerGroup', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/customer/sms-list-for-datatable', ['controller' => 'Admin\SmsProvider', 'action' => 'ajaxListForDatatable']);


            $router->add('/ajax/admin/vehicle/brand/list-for-datatable', ['controller' => 'Admin\Vehicle', 'action' => 'ajaxBrandListForDatatable']);
            $router->add('/ajax/admin/vehicle/list-for-datatable', ['controller' => 'Admin\Vehicle', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/vehicle/tuning/list-for-datatable', ['controller' => 'Admin\Vehicle', 'action' => 'ajaxTuningListForDatatable']);
            $router->add('/ajax/admin/vehicle/tuning/option/list-for-datatable', ['controller' => 'Admin\Vehicle', 'action' => 'ajaxTuningOptionListForDatatable']);
            $router->add('/ajax/admin/vehicle/read-method/list-for-datatable', ['controller' => 'Admin\Vehicle', 'action' => 'ajaxReadMethodListForDatatable']);

            $router->add('/ajax/admin/tuning/list-for-datatable', ['controller' => 'Admin\Tuning', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/tuning/option/list-for-datatable', ['controller' => 'Admin\Tuning', 'action' => 'ajaxOptionListForDatatable']);

            $router->add('/ajax/admin/additional-option/list-for-datatable', ['controller' => 'Admin\AdditionalOption', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/read-methods/list-for-datatable', ['controller' => 'Admin\ReadMethod', 'action' => 'ajaxListForDatatable']);

            $router->add('/ajax/admin/bosch-codes/list-for-datatable', ['controller' => 'Admin\BoschCodes', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/p-codes/list-for-datatable', ['controller' => 'Admin\PCodes', 'action' => 'ajaxListForDatatable']);

            $router->add('/ajax/admin/product/list-for-datatable', ['controller' => 'Admin\Product', 'action' => 'ajaxListForDatatable']);

            $router->add('/ajax/admin/currency/list', ['controller' => 'Admin\Currency', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/currency/exchange-rate/list-for-datatable', ['controller' => 'Admin\ExchangeRate', 'action' => 'ajaxListForDatatable']);

            $router->add('/ajax/admin/country/list', ['controller' => 'Admin\Country', 'action' => 'ajaxListForDatatable']);
            $router->add('/ajax/admin/city/list-for-datatable', ['controller' => 'Admin\Country', 'action' => 'ajaxCityListForDatatable']);
            $router->add('/ajax/admin/user/list', ['controller' => 'Admin\User', 'action' => 'ajaxListForDatatable']);

            $router->add('/ajax/admin/support/send-message/{id}', ['controller' => 'Admin\Support', 'action' => 'ajaxSendMessage']);
            $router->add('/ajax/admin/support/detail/{id}', ['controller' => 'Admin\Support', 'action' => 'ajaxSupportDetail']);

            /*
            $router->add('/admin/support/list', ['controller' => 'Admin\Support', 'action' => 'list']);
            $router->add('/admin/user/list', ['controller' => 'Admin\User', 'action' => 'list']);
            $router->add('/admin/setting/general', ['controller' => 'Admin\Setting', 'action' => 'general']);
             */

            $router->add('/ajax/admin/vehicle/list-for-select', ['controller' => 'Admin\Vehicle', 'action' => 'ajaxListForSelect']);
            $router->add('/ajax/admin/country/list-for-select', ['controller' => 'Admin\Country', 'action' => 'ajaxListForSelect']);
            $router->add('/ajax/admin/category/list-for-select', ['controller' => 'Admin\Category', 'action' => 'ajaxListForSelect']);

        }

        $router->add('/ajax/datatable/language', ['controller' => 'Language', 'action' => 'dataTable']);

    }

    public function api(Router $router)
    {
      $router->add('/api/credits', ['controller' => 'Price', 'action' => 'api']);
    }
}
