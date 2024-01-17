<?php

namespace Pemm\Model;

use Pemm\Core\Container;
use Pemm\Core\Model as BaseModel;
use PDO;

class Setting extends BaseModel
{
    private $id = 1;
    private $siteUrl;
    private $siteName;
    private $logo;
    private $register;
    private $login;
    private $logo2;
    private $favicon;
    private $isMaintenance;
    private $defaultPaymentMethod;
    private $description;
    private $description2;
    private $description3;
    private $evc_api;
    private $evc_username;
    private $evc_pass;
    private $evc_status;
    private $updatedAt;
    private $createdAt;
    private $defaultLanguage;
    private $workingHours;
    private $invoice_prefix;
    private $default_currency_method;
    private $license_key;
    private $paypal_username;
    private $paypal_password;
    private $paypal_signature;
    private $paypal_testmode;
    private $paypal_status;
    private $mollie_apikey;
    private $mollie_testmode;
    private $mollie_status;
    private $stripe_apikey;
    private $stripe_publickey;
    private $stripe_testmode;
    private $stripe_status;
    private $iyzico_apikey;
    private $iyzico_apisecret;
    private $iyzico_testmode;
    private $iyzico_status;
    private $btcpayserver_apikey;
    private $btcpayserver_apisecret;
    private $btcpayserver_storeid;
    private $btcpayserver_host;
    private $btcpayserver_status;
    private $emailSecureType;
    private $emailHost;
    private $emailUsername;
    private $emailPassword;
    private $emailPort;
    private $sms_provider_id;
    private $client_status;
    private $admin_status;
    private $siteLogo;
    private $siteFavicon;
    private $siteEmailLogo;
    private $announcement;
    private $phone;
    private $email;
    private $address;
    private $imprint;
    private $privacy;
    private $googlekey;
    private $googlesecret;
    private $mail_after_register;
    private $duyuru_status;
    private $default_time_zone;

    public function find($id)
    {
        return $this->findOneBy(['filter' => ['id' => $id]]);
    }

    public function findOneBy($criteria)
    {
        return $this->findBy($criteria,true);
    }

    public function findAll($pagination = [])
    {
        return $this->findBy($pagination);
    }

    public function findBy($criteria, $findOne = false)
    {
        $where = $executeData = [];

        if (isset($criteria['filter']['id'])) {
            if (is_array($criteria['filter']['id'])) {
                $where[] = 'setting.id IN (' . implode(',', $criteria['filter']['id']) . ')';
            } else {
                $where[] = 'setting.id=:id';
                $executeData[':id'] = $criteria['filter']['id'];
            }
        }

        $sql = 'SELECT * FROM setting';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        try {

            $prepare = $this->database->prepare($sql);
            $prepare->execute($executeData);
            if ($findOne) {
                $result = null;
                if (!empty($data = $prepare->fetchObject())) {
                    $result = $this->initialize($data);
                }
            } else {
                $result = [];
                $list = $prepare->fetchAll(PDO::FETCH_OBJ);

                if (!empty($list)) {
                    foreach ($list as $key => $data) {
                        $result[$key] = $this->initialize($data);
                    }
                }
            }

        } catch (\Exception $e) {print_r($e);die;}

        return $result;
    }

    /**
     * @param $data
     * @return Setting
     */
    public function initialize($data)
    {
        return (new self())
            ->setId($data->id)
            ->setSiteUrl($data->site_url)
            ->setSiteName($data->site_name)
            ->setLogo($data->logo)
            ->setLogo2($data->logo2)
            ->setRegister($data->register)
            ->setLogin($data->login)
            ->setSiteLogo($data->site_logo)
            ->setSiteFavicon($data->site_favicon)
            ->setSiteEmailLogo($data->site_email_logo)
            ->setPhone($data->phone)
            ->setEmail($data->email)
            ->setAddress($data->address)
            ->setImprint($data->imprint)
            ->setPrivacy($data->privacy)
            ->setGoogleKey($data->googlekey)
            ->setGoogleSecret($data->googlesecret)
            ->setDefaultPaymentMethod($data->default_payment_method)
            ->setFavicon($data->favicon)
            ->setIsMaintenance($data->is_maintenance)
            ->setDescription($data->description)
            ->setDescription2($data->description2)
            ->setDescription3($data->description3)
            ->setEvcapi($data->evc_api)
            ->setEvcusername($data->evc_username)
            ->setEvcpass($data->evc_pass)
            ->setEvcStatus($data->evc_status)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at)
            ->setDefaultLanguage($data->default_language)
            ->setAnnouncement($data->announcement)
            ->setWorkingHours($data->working_hours)
            ->setEmailSecureType($data->email_secure_type)
            ->setEmailHost($data->email_host)
            ->setEmailUsername($data->email_username)
            ->setEmailPassword($data->email_password)
            ->setEmailPort($data->email_port)
            ->setInvoiceprefix($data->invoice_prefix)
            ->setDefault_currency_method($data->default_currency_method)
            ->setLicense_key($data->license_key)
            ->setPaypal_username($data->paypal_username)
            ->setPaypal_password($data->paypal_password)
            ->setPaypal_signature($data->paypal_signature)
            ->setPaypal_testmode($data->paypal_testmode)
            ->setPaypal_status($data->paypal_status)
            ->setMollie_apikey($data->mollie_apikey)
            ->setMollie_testmode($data->mollie_testmode)
            ->setMollie_status($data->mollie_status)
            ->setStripe_apikey($data->stripe_apikey)
            ->setStripe_publickey($data->stripe_publickey)
            ->setStripe_testmode($data->stripe_testmode)
            ->setStripe_status($data->stripe_status)
            ->setIyzico_apikey($data->iyzico_apikey)
            ->setIyzico_apisecret($data->iyzico_apisecret)
            ->setIyzico_testmode($data->iyzico_testmode)
            ->setIyzico_status($data->iyzico_status)
            ->setbtcpayserver_apikey($data->btcpayserver_apikey)
            ->setbtcpayserver_apisecret($data->btcpayserver_apisecret)
            ->setbtcpayserver_storeid($data->btcpayserver_storeid)
            ->setbtcpayserver_host($data->btcpayserver_host)
            ->setBtcpayserver_status($data->btcpayserver_status)
            ->setMailAfterRegister($data->mail_after_register)
            ->setDuyuruStatus($data->duyuru_status)
            ->setSmsProvider($data->sms_provider_id)
            ->setClientStatus($data->client_status)
            ->setAdminStatus($data->admin_status)
            ->setDefaultTimeZone($data->default_time_zone);

    }

    public function store()
    {
        if (empty($this->id)) {
            if ($this->database->insert('setting', [
                'site_url' => $this->siteUrl,
                'site_name' => $this->siteName,
                'logo' => $this->logo,
                'logo2' => $this->logo2,
                'register' => $this->register,
                'login' => $this->login,
                'favicon' => $this->favicon,
                'default_payment_method' => $this->defaultPaymentMethod,
                'is_maintenance' => $this->isMaintenance,
                'default_language' => $this->defaultLanguage,
                'working_hours' => $this->workingHours,
                'description' => $this->description,
                'description2' => $this->description2,
                'description3' => $this->description3,
                'evc_api' => $this->evc_api,
                'evc_username' => $this->evc_username,
                'evc_pass' => $this->evc_pass,
                'evc_status' => $this->evc_status,
                'email_secure_type' => $this->emailSecureType,
                'email_host' => $this->emailHost,
                'email_username' => $this->emailUsername,
                'email_password' => $this->emailPassword,
                'email_port' => $this->emailPort,
                'site_logo' => $this->siteLogo,
                'site_favicon' => $this->siteFavicon,
                'announcement' => $this->announcement,
                'site_email_logo' => $this->siteEmailLogo,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'imprint' => $this->imprint,
                'privacy' => $this->privacy,
                'googlekey' => $this->googlekey,
                'googlesecret' => $this->googlesecret,
                'invoice_prefix' => $this->invoice_prefix,
                'default_currency_method' => $this->default_currency_method,
                'license_key' => $this->license_key,
                'paypal_username' => $this->paypal_username,
                'paypal_password' => $this->paypal_password,
                'paypal_signature' => $this->paypal_signature,
                'paypal_testmode' => $this->paypal_testmode,
                'paypal_status' => $this->paypal_status,
                'mollie_apikey' => $this->mollie_apikey,
                'mollie_testmode' => $this->mollie_testmode,
                'mollie_status' => $this->mollie_status,
                'stripe_apikey' => $this->stripe_apikey,
                'stripe_publickey' => $this->stripe_publickey,
                'stripe_testmode' => $this->stripe_testmode,
                'stripe_status' => $this->stripe_status,
                'iyzico_apikey' => $this->iyzico_apikey,
                'iyzico_apisecret' => $this->iyzico_apisecret,
                'iyzico_testmode' => $this->iyzico_testmode,
                'iyzico_status' => $this->iyzico_status,
                'btcpayserver_apikey' => $this->btcpayserver_apikey,
                'btcpayserver_apisecret' => $this->btcpayserver_apisecret,
                'btcpayserver_storeid' => $this->btcpayserver_storeid,
                'btcpayserver_host' => $this->btcpayserver_host,
                'btcpayserver_status' => $this->btcpayserver_status,
                'sms_provider_id' => $this->sms_provider_id,
                'client_status' => $this->client_status,
                'admin_status' => $this->admin_status,
                'mail_after_register' => $this->mail_after_register,
                'duyuru_status' => $this->duyuru_status,
                'default_time_zone' => $this->default_time_zone,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ])) {
                $this->id = $this->database->lastInsertId();
            }
        } else {
            $this->database->update('setting', [
                'id' => $this->id,
                'site_url' => $this->siteUrl,
                'site_name' => $this->siteName,
                'logo' => $this->logo,
                'logo2' => $this->logo2,
                'register' => $this->register,
                'login' => $this->login,
                'favicon' => $this->favicon,
                'default_payment_method' => $this->defaultPaymentMethod,
                 'is_maintenance' => $this->isMaintenance,
                'working_hours' => $this->workingHours,
                'default_language' => $this->defaultLanguage,
                'description' => $this->description,
                'description2' => $this->description2,
                'description3' => $this->description3,
                'evc_api' => $this->evc_api,
                'evc_username' => $this->evc_username,
                'evc_pass' => $this->evc_pass,
                'evc_status' => $this->evc_status,
                'email_secure_type' => $this->emailSecureType,
                'email_host' => $this->emailHost,
                'email_username' => $this->emailUsername,
                'email_password' => $this->emailPassword,
                'email_port' => $this->emailPort,
                'site_logo' => $this->siteLogo,
                'site_favicon' => $this->siteFavicon,
                'site_email_logo' => $this->siteEmailLogo,
                'announcement' => $this->announcement,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'privacy' => $this->privacy,
                'imprint' => $this->imprint,
                'googlekey' => $this->googlekey,
                'googlesecret' => $this->googlesecret,
                'invoice_prefix' => $this->invoice_prefix,
                'default_currency_method' => $this->default_currency_method,
                'license_key' => $this->license_key,
                'paypal_username' => $this->paypal_username,
                'paypal_password' => $this->paypal_password,
                'paypal_signature' => $this->paypal_signature,
                'paypal_testmode' => $this->paypal_testmode,
                'paypal_status' => $this->paypal_status,
                'mollie_apikey' => $this->mollie_apikey,
                'mollie_testmode' => $this->mollie_testmode,
                'mollie_status' => $this->mollie_status,
                'stripe_apikey' => $this->stripe_apikey,
                'stripe_publickey' => $this->stripe_publickey,
                'stripe_testmode' => $this->stripe_testmode,
                'stripe_status' => $this->stripe_status,
                'iyzico_apikey' => $this->iyzico_apikey,
                'iyzico_apisecret' => $this->iyzico_apisecret,
                'iyzico_testmode' => $this->iyzico_testmode,
                'iyzico_status' => $this->iyzico_status,
                'btcpayserver_apikey' => $this->btcpayserver_apikey,
                'btcpayserver_apisecret' => $this->btcpayserver_apisecret,
                'btcpayserver_storeid' => $this->btcpayserver_storeid,
                'btcpayserver_host' => $this->btcpayserver_host,
                'btcpayserver_status' => $this->btcpayserver_status,
                'sms_provider_id' => $this->sms_provider_id,
                'client_status' => $this->client_status,
                'admin_status' => $this->admin_status,
                'mail_after_register' => $this->mail_after_register,
                'duyuru_status' => $this->duyuru_status,
                'default_time_zone'  => $this->default_time_zone,
                'created_at' => $this->createdAt,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Setting
     */
    public function setId(int $id): Setting
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * @param mixed $siteUrl
     * @return Setting
     */
    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsMaintenance()
    {
        return $this->isMaintenance;
    }

    /**
     * @param mixed $isMaintenance
     * @return Setting
     */
    public function setIsMaintenance($isMaintenance)
    {
        $this->isMaintenance = $isMaintenance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return Setting
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return Setting
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteName()
    {
        return $this->siteName;
    }

    /**
     * @param mixed $siteName
     * @return Setting
     */
    public function setSiteName($siteName)
    {
        $this->siteName = $siteName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     * @return Setting
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFavicon()
    {
        return $this->favicon;
    }

    /**
     * @param mixed $favicon
     * @return Setting
     */
    public function setFavicon($favicon)
    {
        $this->favicon = $favicon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    public function getDescription2()
    {
        return $this->description2;
    }
    public function getDescription3()
    {
        return $this->description3;
    }
    public function getEvcapi()
    {
        return $this->evc_api;
    }
    public function getEvcusername()
    {
        return $this->evc_username;
    }
    public function getEvcpass()
    {
        return $this->evc_pass;
    }
    public function getEvcStatus()
    {
        return $this->evc_status;
    }

    public function getInvoiceprefix()
    {
        return $this->invoice_prefix;
    }
    public function getDefault_currency_method()
    {
        return $this->default_currency_method;
    }

    public function getDefaultTimeZone()
    {
        return $this->default_time_zone;
    }

    public function getLicense_key()
    {
        return $this->license_key;
    }
    public function getPaypal_username()
    {
        return $this->paypal_username;
    }
    public function getSmsProvider()
    {
        return $this->sms_provider_id;
    }
    public function getCustomerStatus()
    {
        return $this->customer_status;
    }
    public function getAdminStatus()
    {
        return $this->admin_status;
    }
    public function getPaypal_password()
    {
        return $this->paypal_password;
    }
    public function getPaypal_signature()
    {
        return $this->paypal_signature;
    }
    public function getPaypal_testmode()
    {
        return $this->paypal_testmode;
    }
    public function getPaypal_status()
    {
        return $this->paypal_status;
    }
    public function getMollie_apikey()
    {
        return $this->mollie_apikey;
    }
    public function getMollie_testmode()
    {
        return $this->mollie_testmode;
    }
    public function getMollie_status()
    {
        return $this->mollie_status;
    }
    public function getStripe_apikey()
    {
        return $this->stripe_apikey;
    }
    public function getStripe_publickey()
    {
        return $this->stripe_publickey;
    }
    public function getStripe_testmode()
    {
        return $this->stripe_testmode;
    }
    public function getStripe_status()
    {
        return $this->stripe_status;
    }
    public function getIyzico_apikey()
    {
        return $this->iyzico_apikey;
    }
    public function getIyzico_apisecret()
    {
        return $this->iyzico_apisecret;
    }
    public function getIyzico_testmode()
    {
        return $this->iyzico_testmode;
    }
    public function getIyzico_status()
    {
        return $this->iyzico_status;
    }
    public function getbtcpayserver_apikey()
    {
        return $this->btcpayserver_apikey;
    }

    public function getBtcpayserver_status()
    {
        return $this->btcpayserver_status;
    }

    public function getbtcpayserver_apisecret()
    {
        return $this->btcpayserver_apisecret;
    }
    public function getbtcpayserver_storeid()
    {
        return $this->btcpayserver_storeid;
    }
    public function getbtcpayserver_host()
    {
        return $this->btcpayserver_host;
    }
    public function getMailAfterRegister()
    {
        return $this->mail_after_register;
    }
    public function getDuyuruStatus()
    {
        return $this->duyuru_status;
    }

    /**
     * @param mixed $description
     * @return Setting
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function setDescription2($description2)
    {
        $this->description2 = $description2;
        return $this;
    }
    public function setDescription3($description3)
    {
        $this->description3 = $description3;
        return $this;
    }
    public function setEvcapi($evc_api)
    {
        $this->evc_api = $evc_api;
        return $this;
    }
    public function setEvcusername($evc_username)
    {
        $this->evc_username = $evc_username;
        return $this;
    }
    public function setEvcpass($evc_pass)
    {
        $this->evc_pass = $evc_pass;
        return $this;
    }
    public function setEvcStatus($evc_status)
    {
        $this->evc_status = $evc_status;
        return $this;
    }

    public function setInvoiceprefix($invoice_prefix)
    {
        $this->invoice_prefix = $invoice_prefix;
        return $this;
    }

    public function setDefault_currency_method($default_currency_method)
    {
        $this->default_currency_method = $default_currency_method;
        return $this;
    }

    public function setDefaultTimeZone($default_time_zone)
    {
        $this->default_time_zone = $default_time_zone;
        return $this;
    }

    public function setLicense_key($license_key)
    {
        $this->license_key = $license_key;
        return $this;
    }
    public function setPaypal_username($paypal_username)
    {
        $this->paypal_username = $paypal_username;
        return $this;
    }
    public function setSmsProvider($sms_provider_id)
    {
        $this->sms_provider_id = $sms_provider_id;
        return $this;
    }
    public function setClientStatus($client_status)
    {
        $this->client_status = $client_status;
        return $this;
    }
    public function setAdminStatus($admin_status)
    {
        $this->admin_status = $admin_status;
        return $this;
    }
    public function setPaypal_password($paypal_password)
    {
        $this->paypal_password = $paypal_password;
        return $this;
    }
    public function setPaypal_signature($paypal_signature)
    {
        $this->paypal_signature = $paypal_signature;
        return $this;
    }
    public function setPaypal_testmode($paypal_testmode)
    {
        $this->paypal_testmode = $paypal_testmode;
        return $this;
    }
    public function setPaypal_status($paypal_status)
    {
        $this->paypal_status = $paypal_status;
        return $this;
    }
    public function setMollie_apikey($mollie_apikey)
    {
        $this->mollie_apikey = $mollie_apikey;
        return $this;
    }
    public function setMollie_testmode($mollie_testmode)
    {
        $this->mollie_testmode = $mollie_testmode;
        return $this;
    }
    public function setMollie_status($mollie_status)
    {
        $this->mollie_status = $mollie_status;
        return $this;
    }
    public function setStripe_apikey($stripe_apikey)
    {
        $this->stripe_apikey = $stripe_apikey;
        return $this;
    }
    public function setStripe_publickey($stripe_publickey)
    {
        $this->stripe_publickey = $stripe_publickey;
        return $this;
    }
    public function setStripe_testmode($stripe_testmode)
    {
        $this->stripe_testmode = $stripe_testmode;
        return $this;
    }
    public function setStripe_status($stripe_status)
    {
        $this->stripe_status = $stripe_status;
        return $this;
    }
    public function setIyzico_apikey($iyzico_apikey)
    {
        $this->iyzico_apikey = $iyzico_apikey;
        return $this;
    }
    public function setIyzico_apisecret($iyzico_apisecret)
    {
        $this->iyzico_apisecret = $iyzico_apisecret;
        return $this;
    }
    public function setIyzico_testmode($iyzico_testmode)
    {
        $this->iyzico_testmode = $iyzico_testmode;
        return $this;
    }

    public function setIyzico_status($iyzico_status)
    {
        $this->iyzico_status = $iyzico_status;
        return $this;
    }
    public function setBtcpayserver_status($btcpayserver_status)
    {
        $this->btcpayserver_status = $btcpayserver_status;
        return $this;
    }
    public function setbtcpayserver_apikey($btcpayserver_apikey)
    {
        $this->btcpayserver_apikey = $btcpayserver_apikey;
        return $this;
    }
    public function setbtcpayserver_apisecret($btcpayserver_apisecret)
    {
        $this->btcpayserver_apisecret = $btcpayserver_apisecret;
        return $this;
    }
    public function setbtcpayserver_storeid($btcpayserver_storeid )
    {
        $this->btcpayserver_storeid  = $btcpayserver_storeid ;
        return $this;
    }
    public function setbtcpayserver_host($btcpayserver_host )
    {
        $this->btcpayserver_host  = $btcpayserver_host ;
        return $this;
    }

    public function setMailAfterRegister($mail_after_register)
    {
        $this->mail_after_register = $mail_after_register;
        return $this;
    }

    public function setDuyuruStatus($duyuru_status)
    {
        $this->duyuru_status = $duyuru_status;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getDefaultPaymentMethod()
    {
        return $this->defaultPaymentMethod;
    }

    /**
     * @param mixed $defaultPaymentMethod
     * @return Setting
     */
    public function setDefaultPaymentMethod($defaultPaymentMethod)
    {
        $this->defaultPaymentMethod = $defaultPaymentMethod;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    /**
     * @param mixed $defaultLanguage
     * @return Setting
     */
    public function setDefaultLanguage($defaultLanguage)
    {
        $this->defaultLanguage = $defaultLanguage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorkingHours($unSerialize = false)
    {
        $workingHours = $this->workingHours;
        if ($unSerialize && !empty($workingHours)) {
            $workingHours = unserialize($workingHours);
        }
        return $workingHours;
    }

    /**
     * @param mixed $workingHours
     * @return Setting
     */
    public function setWorkingHours($workingHours, $serialize = false)
    {
        if ($serialize) {
            $workingHours = serialize($workingHours);
        }
        $this->workingHours = $workingHours;
        return $this;
    }


    public static function paymentMethods()
    {
        return [
            'paypal' => 'Paypal',
            'mollie' => 'Mollie',
            'stripe' => 'Stripe',
            'iyzipay' => 'İyziPay',
            'btcpayserver' => 'BTCPayServer',

        ];
    }

    public static function languages()
    {
        return [
          "tr" => "Türkçe",
          "en" => "English",
          "es" => "Español",
          "de" => "Deutsch",
          "nl" => "Nederlands",
          "ru" => "Русский",
          "ar" => "العربية",
          "pt" => "Português",
          "fr" => "Français",
          "it" => "Italiano",
          "sk" => "Slovenský",
          'gr' =>  'Ελληνικά',
          "hu" => "Magyar",
          "cz" => "Česky",
          "he" => "עִבְרִית‎",
          "po" => "Polski"
        ];
    }



    /**
     * @return mixed
     */
    public function getEmailSecureType()
    {
        return $this->emailSecureType;
    }

    /**
     * @param mixed $emailSecureType
     * @return Setting
     */
    public function setEmailSecureType($emailSecureType)
    {
        $this->emailSecureType = $emailSecureType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailHost()
    {
        return $this->emailHost;
    }

    /**
     * @param mixed $emailHost
     * @return Setting
     */
    public function setEmailHost($emailHost)
    {
        $this->emailHost = $emailHost;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailUsername()
    {
        return $this->emailUsername;
    }

    /**
     * @param mixed $emailUsername
     * @return Setting
     */
    public function setEmailUsername($emailUsername)
    {
        $this->emailUsername = $emailUsername;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailPassword()
    {
        return $this->emailPassword;
    }

    /**
     * @param mixed $emailPassword
     * @return Setting
     */
    public function setEmailPassword($emailPassword)
    {
        $this->emailPassword = $emailPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailPort()
    {
        return $this->emailPort;
    }

    /**
     * @param mixed $emailPort
     * @return Setting
     */
    public function setEmailPort($emailPort)
    {
        $this->emailPort = $emailPort;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteLogo($fullPath = false)
    {
        $siteLogo = $this->siteLogo;

        if ($fullPath) {
            $siteLogo = '/assets/img/' . $siteLogo;
        }
        return $siteLogo;
    }

    /**
     * @param mixed $siteLogo
     * @return Setting
     */
    public function setSiteLogo($siteLogo)
    {
        $this->siteLogo = $siteLogo;
        return $this;
    }


        /**
         * @return mixed
         */
        public function getLogo2($fullPath = false)
        {
            $logo2 = $this->logo2;

            if ($fullPath) {
                $logo2 = '/assets/img/' . $logo2;
            }
            return $logo2;
        }

        /**
         * @param mixed $logo2
         * @return Setting
         */
        public function setLogo2($logo2)
        {
            $this->logo2 = $logo2;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getLogin($fullPath = false)
        {
            $login = $this->login;

            if ($fullPath) {
                $login = '/assets/img/' . $login;
            }
            return $login;
        }

        /**
         * @param mixed $logo2
         * @return Setting
         */
        public function setLogin($login)
        {
            $this->login = $login;
            return $this;
        }
        /**
         * @return mixed
         */
        public function getRegister($fullPath = false)
        {
            $register = $this->register;

            if ($fullPath) {
                $register = '/assets/img/' . $register;
            }
            return $register;
        }

        /**
         * @param mixed $logo2
         * @return Setting
         */
        public function setRegister($register)
        {
            $this->register = $register;
            return $this;
        }
    /**
     * @return mixed
     */
    public function getSiteFavicon($fullPath = false)
    {
        $siteFavicon = $this->siteFavicon;
        if ($fullPath) {
            $siteFavicon = '/assets/img/' . $siteFavicon;
        }
        return $siteFavicon;
    }

    /**
     * @param mixed $siteFavicon
     * @return Setting
     */
    public function setSiteFavicon($siteFavicon)
    {
        $this->siteFavicon = $siteFavicon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteEmailLogo($fullPath = false)
    {
        $siteEmailLogo = $this->siteEmailLogo;
        if ($fullPath) {
            $siteEmailLogo = '/assets/img/' . $siteEmailLogo;
        }
        return $siteEmailLogo;
    }

    /**
     * @param mixed $siteEmailLogo
     * @return Setting
     */
    public function setSiteEmailLogo($siteEmailLogo)
    {
        $this->siteEmailLogo = $siteEmailLogo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnnouncement()
    {
        return $this->announcement;
    }

    /**
     * @param mixed $announcement
     * @return Setting
     */
    public function setAnnouncement($announcement)
    {
        $this->announcement = $announcement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return Setting
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Setting
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Setting
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @param mixed $privacy
     * @return Setting
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getImprint()
    {
        return $this->imprint;
    }

    /**
     * @param mixed $imprint
     * @return Setting
     */
    public function setImprint($imprint)
    {
        $this->imprint = $imprint;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getGoogleSecret()
    {
        return $this->googlesecret;
    }

    /**
     * @param mixed $imprint
     * @return Setting
     */
    public function setGoogleSecret($googlesecret)
    {
        $this->googlesecret = $googlesecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoogleKey()
    {
        return $this->googlekey;
    }

    /**
     * @param mixed $imprint
     * @return Setting
     */
    public function setGoogleKey($googlekey)
    {
        $this->googlekey = $googlekey;
        return $this;
    }



}
