<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action\RecurringPayment;

use Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Response\Action\ConfirmRecurringResponse;
use Checkdomain\TeleCash\IPG\API\Response\ErrorResponse;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class Cancel
 *
 * @package Checkdomain\TeleCash\IPG\API\Action
 */
class Cancel extends Action\RecurringPayment
{
    /**
     * @param OrderService $service
     * @param string       $orderId
     */
    public function __construct(
        OrderService $service,
        $orderId
    )
    {
        parent::__construct($service, self::FUNCTION_CANCEL, null, null, $orderId);
    }

    /**
     * Cancel a recurring payment
     *
     * @return ConfirmRecurringResponse|ErrorResponse
     */
    public function cancel()
    {
        return $this->execute();
    }

}
