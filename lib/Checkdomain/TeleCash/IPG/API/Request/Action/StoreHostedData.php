<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action;

use Checkdomain\TeleCash\IPG\API\Model\DataStorageItem;
use Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Response\Action\Confirm;
use Checkdomain\TeleCash\IPG\API\Response\Error;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class StoreHostedData
 *
 * @package Checkdomain\TeleCash\IPG\API\Action
 */
class StoreHostedData extends Action
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
     * @return Confirm
     * @throws \Exception
     */
    public function store()
    {
        $response = $this->service->IPGApiAction($this);

        return $response instanceof Error ? $response : new Confirm($response);
    }

}
