<?php

namespace Checkdomain\TeleCash\IPG\API\Response\Order;

use Checkdomain\TeleCash\IPG\API\AbstractResponse;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class Sell
 */
class Sell extends AbstractResponse
{

    const RESPONSE_SUCCESS = 'Function performed error-free';

    /** @var string  */
    protected $approvalCode;
    /** @var string  */
    protected $avsResponse;
    /** @var string  */
    protected $brand;
    /** @var string  */
    protected $orderId;
    /** @var string  */
    protected $paymentType;
    /** @var string  */
    protected $processorApprovalCode;
    /** @var string  */
    protected $processorReceiptNumber;
    /** @var string  */
    protected $processorReferenceNumber;
    /** @var string  */
    protected $processorResponse;
    /** @var string  */
    protected $processorResponseCode;
    /** @var string  */
    protected $processorTraceNumber;
    /** @var string  */
    protected $provider;
    /** @var string  */
    protected $tDate;
    /** @var  String */
    protected $terminalId;
    /** @var string  */
    protected $transactionResult;
    /** @var string  */
    protected $transactionTime;

    /**
     * @return string
     */
    public function getApprovalCode()
    {
        return $this->approvalCode;
    }

    /**
     * @return string
     */
    public function getAvsResponse()
    {
        return $this->avsResponse;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @return string
     */
    public function getProcessorApprovalCode()
    {
        return $this->processorApprovalCode;
    }

    /**
     * @return string
     */
    public function getProcessorReceiptNumber()
    {
        return $this->processorReceiptNumber;
    }

    /**
     * @return string
     */
    public function getProcessorReferenceNumber()
    {
        return $this->processorReferenceNumber;
    }

    /**
     * @return string
     */
    public function getProcessorResponse()
    {
        return $this->processorResponse;
    }

    /**
     * @return string
     */
    public function getProcessorResponseCode()
    {
        return $this->processorResponseCode;
    }

    /**
     * @return string
     */
    public function getProcessorTraceNumber()
    {
        return $this->processorTraceNumber;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return string
     */
    public function getTerminalId()
    {
        return $this->terminalId;
    }

    /**
     * @return string
     */
    public function getTDate()
    {
        return $this->tDate;
    }

    /**
     * @return string
     */
    public function getTransactionResult()
    {
        return $this->transactionResult;
    }

    /**
     * @return string
     */
    public function getTransactionTime()
    {
        return $this->transactionTime;
    }

    /**
     * @param \DOMDocument $responseDoc
     *
     * @throws \Exception
     */
    public function __construct(\DOMDocument $responseDoc)
    {
        $this->processorResponse = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorResponseMessage');

        //Request was successful, otherwise an Exception would have been raised in OrderService::checkSoapError

        if ($this->processorResponse != self::RESPONSE_SUCCESS) {
            throw new \Exception('Sell Transaction failed (' . $responseDoc->saveXML() . ')');
        }

        $this->approvalCode             = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ApprovalCode');
        $this->avsResponse              = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'AVSResponse');
        $this->brand                    = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'Brand');
        $this->orderId                  = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'OrderId');
        $this->paymentType              = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'PaymentType');
        $this->processorApprovalCode    = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorApprovalCode');
        $this->processorReceiptNumber   = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorReceiptNumber');
        $this->processorReferenceNumber = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorReferenceNumber');
        $this->processorResponseCode    = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorResponseCode');
        $this->processorTraceNumber     = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorTraceNumber');
        $this->provider                 = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'CommercialServiceProvider');
        $this->tDate                    = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'TDate');
        $this->terminalId               = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'TerminalID');
        $this->transactionTime          = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'TransactionTime');
        $this->transactionResult        = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'TransactionResult');
    }
}
