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

    const TRANSACTION_RESULT_APPROVED       = 'APPROVED';
    const TRANSACTION_RESULT_DECLINED       = 'DECLINED';
    const TRANSACTION_RESULT_FRAUD          = 'FRAUD';
    const TRANSACTION_RESULT_FAILED         = 'FAILED';
    const TRANSACTION_RESULT_NOT_SUCCESSFUL = 'NOT_SUCCESSFUL';

    /** @var bool */
    protected $wasSuccessful;
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
    /** @var string  */
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
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->wasSuccessful;
    }

    /**
     * Checks whether the sell was successful.
     *
     * @param \DOMDocument $responseDoc
     *
     * @return bool
     */
    private function checkIfSuccessful(\DOMDocument $responseDoc)
    {
        $this->wasSuccessful = false;

        $list = $responseDoc->getElementsByTagNameNS(OrderService::NAMESPACE_N3, 'successfully');
        if ($list->length > 0) {
            $success = $responseDoc->getElementsByTagNameNS(OrderService::NAMESPACE_N3, 'successfully')->item(0)->nodeValue;

            $this->wasSuccessful = ($success === 'true');
        } else {
            $list = $responseDoc->getElementsByTagNameNS(OrderService::NAMESPACE_N3, 'ProcessorResponseMessage');
            if ($list->length > 0) {
                $this->wasSuccessful = ($list->item(0)->nodeValue === Sell::RESPONSE_SUCCESS);
            }
        }

        return $this->wasSuccessful;
    }

    /**
     * @param \DOMDocument $responseDoc
     *
     * @throws \Exception
     */
    public function __construct(\DOMDocument $responseDoc)
    {
        if ($this->checkIfSuccessful($responseDoc)) {
            $this->approvalCode             = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ApprovalCode');
            $this->avsResponse              = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'AVSResponse');
            $this->brand                    = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'Brand');
            $this->orderId                  = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'OrderId');
            $this->paymentType              = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'PaymentType');
            $this->processorApprovalCode    = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorApprovalCode');
            $this->processorReceiptNumber   = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorReceiptNumber');
            $this->processorReferenceNumber = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorReferenceNumber');
            $this->processorResponse        = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorResponseMessage');
            $this->processorResponseCode    = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorResponseCode');
            $this->processorTraceNumber     = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'ProcessorTraceNumber');
            $this->provider                 = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'CommercialServiceProvider');
            $this->tDate                    = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'TDate');
            $this->terminalId               = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'TerminalID');
            $this->transactionTime          = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'TransactionTime');
            $this->transactionResult        = $this->firstElementByTagNSString($responseDoc, OrderService::NAMESPACE_N3, 'TransactionResult');
        } else {
            $this->transactionResult     = Sell::TRANSACTION_RESULT_NOT_SUCCESSFUL;
            $this->processorResponseCode = $responseDoc->getElementsByTagNameNS(OrderService::NAMESPACE_N2, 'Error')->item(0)->attributes->getNamedItem('Code')->nodeValue;
            $this->processorResponse     = $responseDoc->getElementsByTagNameNS(OrderService::NAMESPACE_N2, 'ErrorMessage')->item(0)->nodeValue;
        }
    }
}
