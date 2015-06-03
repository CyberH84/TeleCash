<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action;

use Checkdomain\TeleCash\IPG\API\Model\DataStorageItem;
use Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Response\Action\ConfirmResponse;
use Checkdomain\TeleCash\IPG\API\Response\ErrorResponse;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class StoreHostedDataAction
 *
 * @package Checkdomain\TeleCash\IPG\API\Action
 */
class StoreHostedDataAction extends Action
{

    /**
     * @param OrderService    $service
     * @param DataStorageItem $storageItem
     */
    public function __construct(OrderService $service, DataStorageItem $storageItem)
    {
        parent::__construct($service);

        $xml = $this->document->createElement('ns2:StoreHostedData');
        $storageData = $storageItem->getXML($this->document);
        $xml->appendChild($storageData);
        $this->element->getElementsByTagName('ns2:Action')->item(0)->appendChild($xml);
    }

    /**
     * @return ConfirmResponse
     * @throws \Exception
     */
    public function store()
    {
        $response = $this->service->IPGApiAction($this);

        return $response instanceof ErrorResponse ? $response : new ConfirmResponse($response);
    }

}
