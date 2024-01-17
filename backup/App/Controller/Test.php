<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Helper;
use Pemm\Model\Page;
use Pemm\Model\Setting;
use Pemm\Model\Category;
use Pemm\Model\Customer;

use PDO;


class Test extends CoreController
{
    public function webmavie_test() {
        // echo 'Demo';
        $sms = new Sms();
        // $sms->sendSms("+905466367027", "test");
    }
    public function index()
    {
        $option = [
            PDO::ATTR_PERSISTENT => FALSE,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ];

        try {
            $pdo = new \PDO("mysql:host=127.0.0.1;dbname=car","ubuntu","ubuntu", $option);
            /*$brands = $pdo->query('select * from brand')->fetchAll(PDO::FETCH_OBJ);
            $errors = [];
            foreach ($brands as $brand) {
                try {
                    $brandImageName = '';
                    if (!empty($brand->imagelink)) {
                        $brandImage = file_get_contents($brand->imagelink);
                        $extension = pathinfo(parse_url($brand->imagelink, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $brandImageName = 'brand-' . $brand->link . '.' . $extension;
                        file_put_contents('/var/www/r10/proecufilecom/public/images/category/' . $brandImageName , $brandImage);
                    }

                    $prepare = $pdo->prepare('INSERT INTO categories SET parent_id=:parent_id, slug=:slug, name=:name, image=:image, type=:type, gecici=:gecici, sort_order=:sort_order');
                    $prepare->execute([
                        ':parent_id' => 0,
                        ':slug' => $brand->link,
                        ':name' => $brand->name,
                        ':image' => $brandImageName,
                        ':type' => 'brand',
                        ':gecici' => $brand->id,
                        ':sort_order' => $brand->id
                    ]);

                } catch (\Exception $e) {
                    $errors[] = $brand;
                }

            }

            print_r($errors);die;

            $models = $pdo->query('select * from model')->fetchAll(PDO::FETCH_OBJ);
            $errors = [];
            foreach ($models as $model) {
                try {
                    $brand= $pdo->query('select * from categories WHERE type="brand" and gecici=' . $model->brand_id)->fetchObject();
                    if (!empty($brand)) {
                        $prepare = $pdo->prepare('INSERT INTO categories SET parent_id=:parent_id, slug=:slug, name=:name, type=:type, gecici=:gecici, sort_order=:sort_order');
                        $prepare->execute([
                            ':parent_id' => $brand->id,
                            ':slug' => (new Helper())->link($brand->name . '-' . $model->name),
                            ':name' => $model->name,
                            ':type' => 'model',
                            ':gecici' => $model->id,
                            ':sort_order' => $model->id
                        ]);
                    }


                } catch (\Exception $e) {

                    $errors[] = $model;
                }

            }

            print_r($errors);die;

            $generations = $pdo->query('select * from generation')->fetchAll(PDO::FETCH_OBJ);
            $errors = [];
            foreach ($generations as $generation) {
                $s = false;
                if (preg_match('/&gt;/', $generation->name)) {
                    $s = true;
                }

                $generation->name = str_replace('&gt;', '>', $generation->name);

                try {
                    $model = $pdo->query('select * from categories WHERE type="model" and gecici=' . $generation->model_id)->fetchObject();

                    if (!empty($model)) {
                        $prepare = $pdo->prepare('INSERT INTO categories SET parent_id=:parent_id, slug=:slug, name=:name, type=:type, gecici=:gecici, sort_order=:sort_order');
                        $prepare->execute([
                            ':parent_id' => $model->id,
                            ':slug' => (new Helper())->link($model->slug . '-' . $generation->name . ($s ? '-gt': '')),
                            ':name' => $generation->name,
                            ':type' => 'generation',
                            ':gecici' => $generation->id,
                            ':sort_order' => $generation->id
                        ]);
                    }


                } catch (\Exception $e) {

                    $errors[] = $model;
                }

            }

            print_r($errors);die;

            $engines = $pdo->query('select * from engine')->fetchAll(PDO::FETCH_OBJ);
            $errors = [];
            foreach ($engines as $engine) {


                try {
                    $generation = $pdo->query('select * from categories WHERE type="generation" and gecici=' . $engine->generation_id)->fetchObject();

                    if (!empty($generation)) {
                        $prepare = $pdo->prepare('INSERT INTO categories SET parent_id=:parent_id, slug=:slug, name=:name, type=:type, gecici=:gecici, sort_order=:sort_order');
                        $prepare->execute([
                            ':parent_id' => $generation->id,
                            ':slug' => (new Helper())->link($generation->slug . '-' . $engine->name),
                            ':name' => $engine->name,
                            ':type' => 'engine',
                            ':gecici' => $engine->id,
                            ':sort_order' => $engine->id
                        ]);
                    }


                } catch (\Exception $e) {

                    $errors[] = $engine;
                }

            }

            print_r($errors);die;

            $cars = $pdo->query('select * from cars where id > 7628')->fetchAll(PDO::FETCH_OBJ);
            $errors = [];
            foreach ($cars as $car) {

                try {
                    $engine = $pdo->query('select * from categories WHERE type="engine" and gecici=' . $car->engine_id)->fetchObject();

                    if (!empty($engine)) {
                        $prepare = $pdo->prepare('UPDATE cars SET engine_id=:engine_id WHERE id=:id');
                        $prepare->execute([
                            ':id' => $car->id,
                            ':engine_id' => $engine->id
                        ]);
                    }


                } catch (\Exception $e) {

                    $errors[] = $car;
                }

            }

            print_r($errors);die;*/

        } catch (\PDOException $exception) {
            print_r($exception);die;
        }

    }

    public function methods(){

        $option = [
            PDO::ATTR_PERSISTENT => FALSE,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ];

        /*try {
            $pdo = new \PDO("mysql:host=127.0.0.1;dbname=proecufile","ubuntu","ubuntu", $option);
            $vts = $pdo->query('select vehicle_id,read_methods from vehicle_tuning')->fetchAll(PDO::FETCH_OBJ);

            foreach ($vts as $vt) {
                if (!empty($vt->read_methods)) {
                    $readMethod = explode(',', $vt->read_methods);
                    $readMethod = array_filter($readMethod);
                    if (!empty($readMethod)) {
                        foreach ($readMethod as $rm) {
                            $prepare = $pdo->prepare('INSERT INTO vehicle_read_method SET vehicle_id=:vehicle_id, read_method_id=:read_method_id, is_active=1');
                            $prepare->execute([
                                ':vehicle_id' => $vt->vehicle_id,
                                ':read_method_id' => $rm
                            ]);
                        }
                    }
                }
            }

        }catch (\Exception $e) {
            print_r($e);Die;
        }

        try {
            $pdo = new \PDO("mysql:host=127.0.0.1;dbname=proecufile","ubuntu","ubuntu", $option);
            $vehicle_tunings = $pdo->query('select vehicle_id, id, tuning_id, options from vehicle_tuning')->fetchAll(PDO::FETCH_OBJ);

            foreach ($vehicle_tunings as $vehicle_tuning) {
                print_r($vehicle_tuning);
                if (!empty($vehicle_tuning->options)) {
                    $options = explode(',', $vehicle_tuning->options);
                    $options = array_filter($options);
                    if (!empty($options)) {
                        foreach ($options as $option) {
                            $tprepare = $pdo->prepare('select id from tuning_additional_options where tuning_id=:tuning_id and additional_option_id=:additional_option_id');
                            $tprepare->execute([
                                ':tuning_id' => $vehicle_tuning->tuning_id,
                                ':additional_option_id' =>$option
                            ]);
                           if (!empty($tao = $tprepare->fetchColumn())) {
                               $prepare = $pdo->prepare('INSERT INTO vehicle_additional_option SET
                                                            vehicle_id=:vehicle_id,
                                                            vehicle_tuning_id=:vehicle_tuning_id,
                                                            tuning_additional_option_id=:tuning_additional_option_id, is_active=1');
                               $prepare->execute([
                                   ':vehicle_id' => $vehicle_tuning->vehicle_id,
                                   ':vehicle_tuning_id' => $vehicle_tuning->id,
                                   ':tuning_additional_option_id' => $tao,
                               ]);
                             }

                           }

                    }


                }



            }

        }catch (\Exception $e) {
            print_r($e);Die;
        }
*/
        /*foreach ($vehicleMethods as $vehicleMethod) {
            try {

                if (!empty($vehicleMethod->getImageOld())) {
                    $image = file_get_contents('https://chiptuningfile-service.com' . $vehicleMethod->getImageOld());
                    if (!empty($image)) {
                        $extension = pathinfo(parse_url($vehicleMethod->getImageOld(), PHP_URL_PATH), PATHINFO_EXTENSION);
                        $imageNmae = 'method-' . Helper::sef($vehicleMethod->getName() . '-' . $vehicleMethod->getSurname()) . '.' . $extension;
                        file_put_contents('/var/www/r10/proecufilecom/public/images/method/' . $imageNmae , $image);
                        $vehicleMethod->setImage($imageNmae);
                        $vehicleMethod->store();
                    }

                }


            } catch (\Exception $e) {
                print_r($e);die;
                print_r($vehicleMethod);die;
            }

        }*/
    }

    public function createOrder()
    {
        /* @var Customer $customer */
        $customer = $this->container->get('customer');
        $customer->cart();
        $order = $customer->cart->toOrder();
    }
}
