<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action;

use Checkdomain\TeleCash\IPG\API\Model\Payment;
use Checkdomain\TeleCash\IPG\API\Model\RecurringPaymentInformation;
use Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Response\Action\ConfirmRecurring;
use Checkdomain\TeleCash\IPG\API\Response\Error;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class RecurringPayment
 */
abstract class RecurringPayment extends Action
{

    const FUNCTION_INSTALL = 'install';
    const FUNCTION_MODIFY  = 'modify';
    const FUNCTION_CANCEL  = 'cancel';

    /** @var string $function */
    private $function;
    /** @var Payment $payment */
    private $payment;
    /** @var RecurringPaymentInformation $paymentInformation */
    private $paymentInformation;
    /** @var string orderId */
    private $orderId;

    /**
     * @param OrderService                $service
     * @param string                      $function
     * @param Payment                     $payment
     * @param RecurringPaymentInformation $paymentInformation
     * @param string                      $orderId
     */
    public function __construct(
        OrderService $service,
        $function,
        Payment $payment = null,
        RecurringPaymentInformation $paymentInformation = null,
        $orderId = null
    )
    {
        parent::__construct($service);

        $this->function           = $function;
        $this->payment            = $payment;
        $this->paymentInformation = $paymentInformation;
        $this->orderId            = $orderId;

        $xml                   = $this->document->createElement('ns2:RecurringPayment');
        $function              = $this->document->createElement('ns2:Function');
        $function->textContent = $this->function;
        $xml->appendChild($function);

        if ($this->function === self::FUNCTION_MODIFY || $this->function === self::FUNCTION_CANCEL) {
            $orderId              = $this->document->createElement('ns2:OrderId');
            $orderId->textContent = $this->orderId;
            $xml->appendChild($orderId);
        }

        if ($this->paymentInformation !== null) {
            $paymentInformation = $this->paymentInformation->getXML($this->document);
            $xml->appendChild($paymentInformation);
        }

        if ($this->payment !== null) {
            $payment = $this->payment->getXML($this->document);
            $xml->appendChild($payment);
        }

        $this->element->getElementsByTagName('ns2:Action')->item(0)->appendChild($xml);
    }

    /**
     * Execute this action
     *
     * @return ConfirmRecurring|Error
     */
    protected function execute()
    {
        $response = $this->service->IPGApiAction($this);

        return $response instanceof Error ? $response : new ConfirmRecurring($response);
    }

}
