<?php

namespace Checkdomain\TeleCash;

use Checkdomain\TeleCash\IPG\API\Model\CreditCardData;
use Checkdomain\TeleCash\IPG\API\Model\CreditCardItem;
use Checkdomain\TeleCash\IPG\API\Model\DataStorageItem;
use Checkdomain\TeleCash\IPG\API\Model\Payment;
use Checkdomain\TeleCash\IPG\API\Model\RecurringPaymentInformation;
use Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Request\Transaction;
use Checkdomain\TeleCash\IPG\API\Response\ErrorResponse;
use Checkdomain\TeleCash\IPG\API\Response\Action\ConfirmRecurringResponse;
use Checkdomain\TeleCash\IPG\API\Response\Action\ConfirmResponse;
use Checkdomain\TeleCash\IPG\API\Response\Action\DisplayResponse;
use Checkdomain\TeleCash\IPG\API\Response\Action\ValidationResponse;
use Checkdomain\TeleCash\IPG\API\Response\Order\SellResponse;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class TeleCash
 *
 * @package Checkdomain\TeleCash
 */
class TeleCash
{

    private $serviceUrl;

    private $apiUser;
    private $apiPass;

    private $clientCertPath;
    private $clientKeyPath;
    private $clientKeyPassPhrase;

    private $serverCert;

    private $myService = null;

    private $debug = false;

    /**
     * Constructor
     *
     * @param string $serviceUrl
     * @param string $apiUser
     * @param string $apiPass
     * @param string $clientCert
     * @param string $clientKey
     * @param string $clientKeyPassPhrase
     * @param string $serverCert
     */
    public function __construct($serviceUrl, $apiUser, $apiPass, $clientCert, $clientKey, $clientKeyPassPhrase, $serverCert)
    {
        $this->serviceUrl          = $serviceUrl;
        $this->apiUser             = $apiUser;
        $this->apiPass             = $apiPass;
        $this->clientCertPath      = $clientCert;
        $this->clientKeyPath       = $clientKey;
        $this->clientKeyPassPhrase = $clientKeyPassPhrase;
        $this->serverCert          = $serverCert;
    }

    /**
     * Set debug mode
     *
     * @param bool $debug
     */
    public function setDebugMode($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Validate credit card information
     *
     * @param string $ccNumber
     * @param string $ccValid
     *
     * @return ValidationResponse|ErrorResponse
     * @throws \Exception
     */
    public function validate($ccNumber, $ccValid)
    {
        $service = $this->getService();

        $validMonth     = substr($ccValid, 0, 2);
        $validYear      = substr($ccValid, 3, 4);
        $ccData         = new CreditCardData($ccNumber, $validMonth, $validYear);
        $validateAction = new Action\Validate($service, $ccData);

        return $validateAction->validate();
    }

    /**
     * Store credit card information externally
     *
     * @param string $ccNumber
     * @param string $ccValid
     * @param string $hostedDataId
     *
     * @return ConfirmResponse|ErrorResponse
     * @throws \Exception
     */
    public function storeHostedData($ccNumber, $ccValid, $hostedDataId)
    {
        $service = $this->getService();

        $validMonth  = substr($ccValid, 0, 2);
        $validYear   = substr($ccValid, 3, 4);
        $ccData      = new CreditCardData($ccNumber, $validMonth, $validYear);
        $ccItem      = new CreditCardItem($ccData, $hostedDataId);
        $storeAction = new Action\StoreHostedData($service, $ccItem);

        return $storeAction->store();
    }

    /**
     * Display externally stored data
     *
     * @param string $hostedDataId
     *
     * @return DisplayResponse|ErrorResponse
     * @throws \Exception
     */
    public function displayHostedData($hostedDataId)
    {
        $service = $this->getService();

        $storageItem   = new DataStorageItem($hostedDataId);
        $displayAction = new Action\DisplayHostedData($service, $storageItem);

        return $displayAction->display();
    }

    /**
     * Validate externally store data
     *
     * @param string $hostedDataId
     *
     * @return ValidationResponse|ErrorResponse
     * @throws \Exception
     */
    public function validateHostedData($hostedDataId)
    {
        $service = $this->getService();

        $payment        = new Payment($hostedDataId);
        $validateAction = new Action\ValidateHostedData($service, $payment);

        return $validateAction->validate();
    }

    /**
     * Delete externally store data
     *
     * @param string $hostedDataId
     *
     * @return ConfirmResponse|ErrorResponse
     * @throws \Exception
     */
    public function deleteHostedData($hostedDataId)
    {
        $service = $this->getService();

        $storageItem  = new DataStorageItem($hostedDataId);
        $deleteAction = new Action\DeleteHostedData($service, $storageItem);

        return $deleteAction->delete();
    }

    /**
     * Make a sale using a previously stored credit card information
     *
     * @param string $hostedDataId
     * @param float  $amount
     *
     * @return SellResponse|ErrorResponse
     * @throws \Exception
     */
    public function sellUsingHostedData($hostedDataId, $amount)
    {
        $service = $this->getService();

        $payment    = new Payment($hostedDataId, $amount);
        $sellAction = new Transaction\SellHostedData($service, $payment);

        return $sellAction->sell();
    }

    /**
     * Install a recurring payment.
     *
     * @param string    $hostedDataId
     * @param float     $amount
     * @param \DateTime $startDate
     * @param int       $count
     * @param int       $frequency
     * @param string    $period
     *
     * @return ConfirmRecurringResponse|ErrorResponse
     */
    public function installRecurringPayment($hostedDataId, $amount, \DateTime $startDate, $count, $frequency, $period)
    {
        $service = $this->getService();

        $paymentInformation     = new RecurringPaymentInformation($startDate, $count, $frequency, $period);
        $payment                = new Payment($hostedDataId, $amount);
        $recurringPaymentAction = new Action\RecurringPayment\Install($service, $payment, $paymentInformation);

        return $recurringPaymentAction->install();
    }

    /**
     * Install a recurring payment, which will only result in a single immediate payment.
     *
     * This is a work around for sth.
     *
     * @param string $hostedDataId
     * @param float  $amount
     *
     * @return ConfirmRecurringResponse|ErrorResponse
     */
    public function installOneTimeRecurringPayment($hostedDataId, $amount)
    {
        return $this->installRecurringPayment($hostedDataId, $amount, new \DateTime(), 1, 1, RecurringPaymentInformation::PERIOD_MONTH);
    }

    /**
     * Modify a recurring payment
     *
     * @param string         $orderId
     * @param string         $hostedDataId
     * @param float          $amount
     * @param \DateTime|null $startDate
     * @param int            $count
     * @param int            $frequency
     * @param string         $period
     *
     * @return ConfirmRecurringResponse|ErrorResponse
     */
    public function modifyRecurringPayment($orderId, $hostedDataId, $amount, $startDate, $count, $frequency, $period)
    {
        $service = $this->getService();

        $paymentInformation     = new RecurringPaymentInformation($startDate, $count, $frequency, $period);
        $payment                = new Payment($hostedDataId, $amount);
        $recurringPaymentAction = new Action\RecurringPayment\Modify($service, $orderId, $payment, $paymentInformation);

        return $recurringPaymentAction->modify();
    }

    /**
     * Cancel a recurring payment
     *
     * @param string $orderId
     *
     * @return ConfirmRecurringResponse|ErrorResponse
     */
    public function cancelRecurringPayment($orderId)
    {
        $service = $this->getService();

        $recurringPaymentAction = new Action\RecurringPayment\Cancel($service, $orderId);

        return $recurringPaymentAction->cancel();
    }


    /**
     * Get a handle to the OrderService
     *
     * @return OrderService
     */
    private function getService()
    {
        if ($this->myService === null) {
            $curlOptions = [
                'url'          => $this->serviceUrl,
                'sslCert'      => $this->clientCertPath,
                'sslKey'       => $this->clientKeyPath,
                'sslKeyPasswd' => $this->clientKeyPassPhrase,
                'caInfo'       => $this->serverCert
            ];
            $this->myService = new OrderService($curlOptions, $this->apiUser, $this->apiPass, $this->debug);
        }

        return $this->myService;
    }

}
