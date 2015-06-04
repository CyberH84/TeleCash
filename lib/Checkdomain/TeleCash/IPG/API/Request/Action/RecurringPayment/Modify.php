<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action\RecurringPayment;

use Checkdomain\TeleCash\IPG\API\Model\Payment;
use Checkdomain\TeleCash\IPG\API\Model\RecurringPaymentInformation;
use Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Response\Action\ConfirmRecurring;
use Checkdomain\TeleCash\IPG\API\Response\Error;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class Modify
 */
class Modify extends Action\RecurringPayment
{
    /**
     * @param OrderService                $service
     * @param string                      $orderId
     * @param Payment                     $payment
     * @param RecurringPaymentInformation $paymentInformation
     */
    public function __construct(
        OrderService $service,
        $orderId,
        Payment $payment = null,
        RecurringPaymentInformation $paymentInformation = null
    )
    {
        parent::__construct($service, self::FUNCTION_MODIFY, $payment, $paymentInformation, $orderId);
    }

    /**
     * Modify a recurring payment
     *
     * @return ConfirmRecurring|Error
     */
    public function modify()
    {
        return $this->execute();
    }

}
