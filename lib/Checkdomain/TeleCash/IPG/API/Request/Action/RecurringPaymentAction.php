<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action;

use Checkdomain\TeleCash\IPG\API\Model\Payment;
use Checkdomain\TeleCash\IPG\API\Model\RecurringPaymentInformation;
use Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Response\Action\ConfirmRecurringResponse;
use Checkdomain\TeleCash\IPG\API\Response\ErrorResponse;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class RecurringPaymentAction
 *
 * @package Checkdomain\TeleCash\IPG\API\Action
 */
class RecurringPaymentAction extends Action
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
     * @param Payment                     $payment
     * @param RecurringPaymentInformation $paymentInformation
     */
    public function __construct(
        OrderService $service,
        Payment $payment = null,
        RecurringPaymentInformation $paymentInformation = null
    )
    {
        parent::__construct($service);

        $this->payment            = $payment;
        $this->paymentInformation = $paymentInformation;
    }

    /**
     * Install a recurring payment
     *
     * @return ConfirmRecurringResponse|ErrorResponse
     */
    public function install()
    {
        $this->function = self::FUNCTION_INSTALL;

        return $this->execute();
    }

    /**
     * Modify a recurring payment
     *
     * @param string $orderId
     *
     * @return ConfirmRecurringResponse|ErrorResponse
     */
    public function modify($orderId)
    {
        $this->function = self::FUNCTION_MODIFY;
        $this->orderId  = $orderId;

        return $this->execute();
    }

    /**
     * Cancel a recurring payment
     *
     * @param string $orderId
     *
     * @return ConfirmRecurringResponse|ErrorResponse
     */
    public function cancel($orderId)
    {
        $this->function = self::FUNCTION_CANCEL;
        $this->orderId  = $orderId;

        return $this->execute();
    }

    /**
     * Execute this action
     *
     * @return ConfirmRecurringResponse|ErrorResponse
     */
    private function execute()
    {
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
        $response = $this->service->IPGApiAction($this);

        return $response instanceof ErrorResponse ? $response : new ConfirmRecurringResponse($response);
    }

}
