<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Transaction;

use Checkdomain\TeleCash\IPG\API\Model\Payment;
use Checkdomain\TeleCash\IPG\API\Model\TransactionDetails;
use Checkdomain\TeleCash\IPG\API\Request\Transaction;
use Checkdomain\TeleCash\IPG\API\Response\Error;
use Checkdomain\TeleCash\IPG\API\Response\Order\Sell;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class SellHostedData
 */
class SellHostedData extends Transaction
{

    /**
     * @param OrderService            $service
     * @param Payment                 $payment
     * @param TransactionDetails|null $transactionDetails
     */
    public function __construct(OrderService $service, Payment $payment, TransactionDetails $transactionDetails = null)
    {
        parent::__construct($service);

        $ccTxType = $this->document->createElement('ns1:CreditCardTxType');
        $ccType   = $this->document->createElement('ns1:Type');
        $ccType->nodeValue = 'sale';
        $ccTxType->appendChild($ccType);
        $paymentData = $payment->getXML($this->document);
        $this->getTransactionElement()->appendChild($ccTxType);
        $this->getTransactionElement()->appendChild($paymentData);

        if (null !== $transactionDetails) {
            $transactionDetailsData = $transactionDetails->getXML($this->document);
            $this->getTransactionElement()->appendChild($transactionDetailsData);
        }
    }

    /**
     * @return Sell|Error
     * @throws \Exception
     */
    public function sell()
    {
        $response = $this->service->IPGApiOrder($this);

        return $response instanceof Error ? $response : new Sell($response);
    }

}
