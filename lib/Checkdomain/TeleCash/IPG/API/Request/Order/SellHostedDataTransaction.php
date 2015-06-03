<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Order;

use Checkdomain\TeleCash\IPG\API\Model\Payment;
use Checkdomain\TeleCash\IPG\API\Request\Transaction;
use Checkdomain\TeleCash\IPG\API\Response\ErrorResponse;
use Checkdomain\TeleCash\IPG\API\Response\Order\SellResponse;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class SellHostedDataTransaction
 *
 * @package Checkdomain\TeleCash\IPG\API\Transaction
 */
class SellHostedDataTransaction extends Transaction
{

    /**
     * @param OrderService $service
     * @param Payment      $payment
     */
    public function __construct(OrderService $service, Payment $payment)
    {
        parent::__construct($service);

        $ccTxType = $this->document->createElement('ns1:CreditCardTxType');
        $ccType = $this->document->createElement('ns1:Type');
        $ccType->nodeValue = 'sale';
        $ccTxType->appendChild($ccType);
        $paymentData = $payment->getXML($this->document);
        $this->element->getElementsByTagName('ns1:Transaction')->item(0)->appendChild($ccTxType);
        $this->element->getElementsByTagName('ns1:Transaction')->item(0)->appendChild($paymentData);
    }

    /**
     * @return SellResponse|ErrorResponse
     * @throws \Exception
     */
    public function sell()
    {
        $response = $this->service->IPGApiOrder($this);

        return $response instanceof ErrorResponse ? $response : new SellResponse($response);
    }

}
