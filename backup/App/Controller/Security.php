<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Symfony\Component\HttpFoundation\Session\Session;
use Pemm\Model\Helper;

class Security extends CoreController
{
    public function createCode()
    {
        /* @var Session $session */
        $session = $this->container->get('session');

        $image = imagecreate(150, 50);
        imagecolorallocate($image, rand(220, 240), rand(220, 240), rand(220, 240));

        $seperator = ['/', '-', '.'];

        $string = Helper::generateRandomString(6);
        $session->set('security_code', $string);

        $x = 5;
        foreach (str_split($string) as $character) {

            $x += rand(15, 20);

            imagestring(
                $image,
                5,
                $x,
                rand(15, 25),
                $character,
                imagecolorallocate($image, rand(0, 160), rand(0, 160), rand(0, 160))
            );

            imagestring(
                $image,
                rand(1,3),
                ($x + 5),
                rand(5, 30),
                $seperator[array_rand($seperator)],
                imagecolorallocate($image, 190, 190, 190)
            );
        }

        header('Content-type: image/png');
        imagepng($image);


    }
}
