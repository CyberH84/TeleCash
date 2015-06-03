<?php

namespace Checkdomain\TeleCash\IPG\API\Request;

use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class Action
 *
 * @package Checkdomain\TeleCash\IPG\API
 */
class Action extends ActionRequest
{

    /**
     * @param OrderService $service
     */
    public function __construct(OrderService $service)
    {
        parent::__construct($service);
        $this->element->appendChild($this->document->createElement('ns2:Action'));
    }

}
