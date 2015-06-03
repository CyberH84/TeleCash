<?php

namespace Checkdomain\TeleCash\IPG\API\Response\Action;

use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class DisplayResponse
 *
 * @package Checkdomain\TeleCash\IPG\API\Action
 */
class DisplayResponse extends AbstractActionResponse
{

    /**
     * @var string
     */
    protected $ccNumber;

    /**
     * @var string
     */
    protected $ccValid;

    /**
     * @var string
     */
    protected $hostedDataId;

    /**
     * @return string
     */
    public function getCCNumber()
    {
        return $this->ccNumber;
    }

    /**
     * @return string
     */
    public function getCCValid()
    {
        return $this->ccValid;
    }

    /**
     * @return string
     */
    public function getHostedDataId()
    {
        return $this->hostedDataId;
    }

    /**
     * @param \DOMDocument $responseDoc
     *
     * @throws \Exception
     */
    public function __construct(\DOMDocument $responseDoc)
    {
        $actionResponse = $responseDoc->getElementsByTagNameNS(OrderService::NAMESPACE_N3, 'IPGApiActionResponse');
        $success        = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'successfully');
        $error          = $responseDoc->getElementsByTagNameNS(OrderService::NAMESPACE_N2, 'Error');

        if ($actionResponse->length > 0 && $success === 'true') {
            if ($error->length === 0) {
                $this->wasSuccessful = true;
                $ccData = $responseDoc->getElementsByTagNameNS(OrderService::NAMESPACE_N2, 'CreditCardData');
                if ($ccData->length > 0) {
                    $this->ccNumber     = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N1, 'CardNumber');
                    $expMonth           = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N1, 'ExpMonth');
                    $expYear            = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N1, 'ExpYear');
                    $this->ccValid      = $expMonth . '/' . $expYear;
                    $this->hostedDataId = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N2, 'HostedDataID');
                }
            } else {
                $this->errorMessage = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N2, 'ErrorMessage');
            }
        } else {
            throw new \Exception("Display Call failed " . $responseDoc->saveXML());
        }
    }
}
