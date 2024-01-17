<?php

namespace Pemm\Controller;

use Pemm\Core\Controller as CoreController;
use Symfony\Component\HttpFoundation\JsonResponse;

class Language extends CoreController
{
    public function dataTable()
    {
        return (new JsonResponse([ "sDecimal" => ",",
            "sEmptyTable" => $this->language::translate('No data available in the table'),
            "sInfo" => $this->language::translate('Showing records from _TOTAL_ to _START_ to _END_'),
            "sInfoEmpty" => $this->language::translate('No Records Found'),
            "sInfoFiltered" => $this->language::translate('(found in _MAX_ record)'),
            "sInfoPostFix" =>    "",
            "sInfoThousands" =>  ".",
            "sLengthMenu" => $this->language::translate('Show _MENU_ record on page'),
            "sLoadingRecords" => $this->language::translate('Loading...'),
            "sProcessing" => $this->language::translate('Processing...'),
            "sSearch" => $this->language::translate('Search'),
            "sZeroRecords" => $this->language::translate('No matching records found'),
            "oPaginate" => [
                "sFirst" => $this->language::translate('First'),
                "sLast" => $this->language::translate('Last'),
                "sNext" => $this->language::translate('sNext'),
                "sPrevious" => $this->language::translate('sPrevious')
            ]
        ]))->send();

    }
}
