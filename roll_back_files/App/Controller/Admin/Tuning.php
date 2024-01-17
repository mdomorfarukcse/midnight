<?php

namespace Pemm\Controller\Admin;

use Pemm\Core\Controller as CoreController;
use Pemm\Core\View;
use Pemm\Model\Page;
use Pemm\Model\TuningAdditionalOption;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pemm\Model\Tuning as TuningModel;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Tuning extends CoreController
{
    public function list()
    {
        $this->container->set('page',
            (new Page())
                ->setType('tuning-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Tunings - ' . $this->setting->getSiteName())
                ->setMetaDescription('Tunings - ' . $this->setting->getDescription())
        );

        View::render('admin','tuning-list', []);
    }

    public function optionList()
    {
        $this->container->set('page',
            (new Page())
                ->setType('tuning-option-list')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Tuning Options - ' . $this->setting->getSiteName())
                ->setMetaDescription('Tuning Options - ' . $this->setting->getDescription())
        );

        View::render('admin','tuning-additional-option-list', []);
    }

    public function form()
    {
        if (!empty($id = @$this->route_params['id'])) {
            $this->container->set('detailId', $id);
        }

        $this->container->set('page',
            (new Page())
                ->setType('tuning-detail')
                ->setUrl($this->request->getBasePath() . $this->request->getRequestUri())
                ->setMetaTitle('Tuning Detail - ' . $this->setting->getSiteName())
                ->setMetaDescription('Tuning Detail - ' . $this->setting->getDescription())
        );

        View::render('admin','tuning', []);

    }

    public function ajaxListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $tuningList = [];

        $tuningModel = new TuningModel();
        $tunings = $tuningModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($tunings)) {
            /* @var TuningModel $tuning */
            foreach ($tunings as $key => $tuning) {
                $tuningList[$key] = [
                    'id' => $tuning->getId(),
                    'code' => $tuning->getCode(),
                    'name' => $tuning->getName(),
                    'is_active' => $tuning->getIsActive(),
                    'credit' => $tuning->getCredit()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $tuningModel->queryTotalCount,
            'recordsFiltered' => $tuningModel->queryTotalCount,
            'data' => $tuningList
        ]))->send();
    }

    public function ajaxOptionListForDatatable()
    {
        $filter = $this->request->request->get('filter');
        $pagination = $this->request->request->get('pagination');
        $order = $this->request->request->get('order');

        $tuningAdditionalOptionList = [];

        $tuningAdditionalOptionModel = new TuningAdditionalOption();
        $tuningAdditionalOptions = $tuningAdditionalOptionModel->findBy([
            'filter' => $filter,
            'pagination' => $pagination,
            'order' => $order
        ]);

        if (!empty($tuningAdditionalOptions)) {
            /* @var TuningAdditionalOption $tuningAdditionalOption */
            foreach ($tuningAdditionalOptions as $key => $tuningAdditionalOption) {
                $tuningAdditionalOptionList[$key] = [
                    'id' => $tuningAdditionalOption->getId(),
                    'tuning_id' => $tuningAdditionalOption->getTuningId(),
                    'additional_option_id' => $tuningAdditionalOption->getAdditionalOption(),
                    'credit' => $tuningAdditionalOption->getCredit(),
                    'is_active' => $tuningAdditionalOption->getIsActive()
                ];
            }
        }

        return (new JsonResponse([
            'draw' => $pagination['draw'],
            'page' => $pagination['page'],
            'recordsTotal' => $tuningAdditionalOptionModel->queryTotalCount,
            'recordsFiltered' => $tuningAdditionalOptionModel->queryTotalCount,
            'data' => $tuningAdditionalOptionList
        ]))->send();
    }
}
