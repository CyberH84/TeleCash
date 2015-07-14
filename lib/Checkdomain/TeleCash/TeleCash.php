<?php

namespace Checkdomain\TeleCash;

use Checkdomain\TeleCash\IPG\API\Model;
use Checkdomain\TeleCash\IPG\API\Request;
use Checkdomain\TeleCash\IPG\API\Response;
use Checkdomain\TeleCash\IPG\API\Service\OrderService;

/**
 * Class TeleCash
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
     * @return Response\Action\Validation|Response\Error
     * @throws \Exception
     */
    public function validate($ccNumber, $ccValid)
    {
        $service = $this->getService();

        $validMonth     = substr($ccValid, 0, 2);
        $validYear      = substr($ccValid, 3, 4);
        $ccData         = new Model\CreditCardData($ccNumber, $validMonth, $validYear);
        $validateAction = new Request\Action\Validate($service, $ccData);

        return $validateAction->validate();
    }

    /**
     * Store credit card information externally
     *
     * @param string $ccNumber
     * @param string $ccValid
     * @param string $hostedDataId
     *
     * @return Response\Action\Confirm|Response\Error
     * @throws \Exception
     */
    public function storeHostedData($ccNumber, $ccValid, $hostedDataId)
    {
        $service = $this->getService();

        $validMonth  = substr($ccValid, 0, 2);
        $validYear   = substr($ccValid, 3, 4);
        $ccData      = new Model\CreditCardData($ccNumber, $validMonth, $validYear);
        $ccItem      = new Model\CreditCardItem($ccData, $hostedDataId);
        $storeAction = new Request\Action\StoreHostedData($service, $ccItem);

        return $storeAction->store();
    }

    /**
     * Display externally stored data
     *
     * @param string $hostedDataId
     *
     * @return Response\Action\Display|Response\Error
     * @throws \Exception
     */
    public function displayHostedData($hostedDataId)
    {
        $service = $this->getService();

        $storageItem   = new Model\DataStorageItem($hostedDataId);
        $displayAction = new Request\Action\DisplayHostedData($service, $storageItem);

        return $displayAction->display();
    }

    /**
     * Validate externally store data
     *
     * @param string $hostedDataId
     *
     * @return Response\Action\Validation|Response\Error
     * @throws \Exception
     */
    public function validateHostedData($hostedDataId)
    {
        $service = $this->getService();

        $payment        = new Model\Payment($hostedDataId);
        $validateAction = new Request\Action\ValidateHostedData($service, $payment);

        return $validateAction->validate();
    }

    /**
     * Delete externally store data
     *
     * @param string $hostedDataId
     *
     * @return Response\Action\Confirm|Response\Error
     * @throws \Exception
     */
    public function deleteHostedData($hostedDataId)
    {
        $service = $this->getService();

        $storageItem  = new Model\DataStorageItem($hostedDataId);
        $deleteAction = new Request\Action\DeleteHostedData($service, $storageItem);

        return $deleteAction->delete();
    }

    /**
     * Make a sale using a previously stored credit card information
     *
     * @param string $hostedDataId
     * @param float  $amount
     *
     * @return Response\Order\Sell|Response\Error
     * @throws \Exception
     */
    public function sellUsingHostedData($hostedDataId, $amount)
    {
        $service = $this->getService();

        $payment    = new Model\Payment($hostedDataId, $amount);
        $sellAction = new Request\Transaction\SellHostedData($service, $payment);

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
     * @return Response\Order\Sell|Response\Error
     * @throws \Exception
     */
    public function installRecurringPayment($hostedDataId, $amount, \DateTime $startDate, $count, $frequency, $period)
    {
        $service = $this->getService();

        $paymentInformation     = new Model\RecurringPaymentInformation($startDate, $count, $frequency, $period);
        $payment                = new Model\Payment($hostedDataId, $amount);
        $recurringPaymentAction = new Request\Action\RecurringPayment\Install($service, $payment, $paymentInformation);

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
     * @return Response\Order\Sell|Response\Error
     * @throws \Exception
     */
    public function installOneTimeRecurringPayment($hostedDataId, $amount)
    {
        return $this->installRecurringPayment($hostedDataId, $amount, new \DateTime(), 1, 1, Model\RecurringPaymentInformation::PERIOD_MONTH);
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
     * @return Response\Action\ConfirmRecurring|Response\Error
     * @throws \Exception
     */
    public function modifyRecurringPayment($orderId, $hostedDataId, $amount, $startDate, $count, $frequency, $period)
    {
        $service = $this->getService();

        $paymentInformation     = new Model\RecurringPaymentInformation($startDate, $count, $frequency, $period);
        $payment                = new Model\Payment($hostedDataId, $amount);
        $recurringPaymentAction = new Request\Action\RecurringPayment\Modify($service, $orderId, $payment, $paymentInformation);

        return $recurringPaymentAction->modify();
    }

    /**
     * Cancel a recurring payment
     *
     * @param string $orderId
     *
     * @return Response\Action\ConfirmRecurring|Response\Error
     * @throws \Exception
     */
    public function cancelRecurringPayment($orderId)
    {
        $service = $this->getService();

        $recurringPaymentAction = new Request\Action\RecurringPayment\Cancel($service, $orderId);

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
