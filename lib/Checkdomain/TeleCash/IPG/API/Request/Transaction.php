<?php

namespace Checkdomain\TeleCash\IPG\API\Request;

use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class Transaction
 */
class Transaction extends OrderRequest
{

    /**
     * @param OrderService $service
     */
    public function __construct(OrderService $service)
    {
        parent::__construct($service);

        $this->element->appendChild($this->document->createElement('ns1:Transaction'));
    }

}
