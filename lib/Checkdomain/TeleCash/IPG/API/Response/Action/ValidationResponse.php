<?php

namespace Checkdomain\TeleCash\IPG\API\Response\Action;

use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class ValidationResponse
 *
 * @package Checkdomain\TeleCash\IPG\API\Action
 */
class ValidationResponse extends AbstractActionResponse
{

    /**
     * @param \DOMDocument $responseDoc
     *
     * @throws \Exception
     */
    public function __construct(\DOMDocument $responseDoc)
    {
        $actionResponse = $responseDoc->getElementsByTagNameNS(OrderService::NAMESPACE_N3, 'IPGApiActionResponse');
        $success        = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'successfully');

        if ($actionResponse->length > 0) {
            $this->wasSuccessful = ($success === 'true');
        } else {
            throw new \Exception("Validate Call failed " . $responseDoc->saveXML());
        }
    }

}
