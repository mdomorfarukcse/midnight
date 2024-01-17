<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Customer;
use Pemm\Model\Page;
use Pemm\Model\Support;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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
        if (!empty($id = $this->route_params['id'])) {

            $support = (new Support())->find($id);
            if (
                !empty($support) &&
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
