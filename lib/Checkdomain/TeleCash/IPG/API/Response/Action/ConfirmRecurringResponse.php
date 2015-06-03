<?php

namespace Checkdomain\TeleCash\IPG\API\Response\Action;

use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class ConfirmRecurringResponse
 *
 * @package Checkdomain\TeleCash\IPG\API\Action
 */
class ConfirmRecurringResponse extends ValidationResponse
{
    /** @var string $orderId */
    private $orderId;

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param \DOMDocument $responseDoc
     *
     * @throws \Exception
     */
    public function __construct(\DOMDocument $responseDoc)
    {
        parent::__construct($responseDoc);

        if ($this->wasSuccessful()) {
            $this->orderId = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'OrderId');
        } else {
            $this->errorMessage = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N2, 'ErrorMessage');
        }
    }
}
