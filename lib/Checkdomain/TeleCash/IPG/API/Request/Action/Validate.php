<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action;

use Checkdomain\TeleCash\IPG\API\Model\CreditCardData;
use Checkdomain\TeleCash\IPG\API\Model\Payment;
use Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Response\Error;
use Checkdomain\TeleCash\IPG\API\Response\Action\Validation;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class Validate
 */
class Validate extends Action
{

    /**
     * @param OrderService   $service
     * @param CreditCardData $creditCardData
     * @param float          $amount
     */
    public function __construct(OrderService $service, CreditCardData $creditCardData, $amount = 1.0)
    {
        parent::__construct($service);

        $xml    = $this->document->createElement('ns2:Validate');
        $ccData = $creditCardData->getXML($this->document);
        $xml->appendChild($ccData);

        if ($amount > 1.0) {
            $payment     = new Payment(null, $amount);
            $paymentData = $payment->getXML($this->document);
            $xml->appendChild($paymentData);
        }

        $this->element->getElementsByTagName('ns2:Action')->item(0)->appendChild($xml);
    }

    /**
     * @return Validation|Error
     * @throws \Exception
     */
    public function validate()
    {
        $response = $this->service->IPGApiAction($this);

        return $response instanceof Error ? $response : new Validation($response);
    }

}
