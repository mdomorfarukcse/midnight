<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Pemm\Model\Customer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Pemm\Model\CustomerVehicle;
use Pemm\Model\Support;

class File extends CoreController
{
  public function download()
  {
      if(!empty($file = $this->request->query->get('file'))) {
          $response = new BinaryFileResponse(PUBLIC_DIR . 'files/' . $file);
          $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
          $response->send();
      }
  }

    public function supportFileDownload()
    {
        /* @var Customer $customer */
        $customer = $this->container->get('customer');

        if (!empty($id = $this->route_params['id'])) {

            $support = (new Support())->find($id);

            if (
                !empty($support) &&
                $support->getCustomer()->getId() == $customer->getId() &&
                !empty($support->getFile())
            ) {
                $file = $this->request->query->get('file');

                $response = new BinaryFileResponse(PUBLIC_DIR . 'files/' . $support->getFile());

                if ($response != null) {
                    $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
                    $response->send();
                }
            }
        }
    }
}
